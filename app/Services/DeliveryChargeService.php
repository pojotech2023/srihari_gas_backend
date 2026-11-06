<?php

namespace App\Services;

use App\Models\Address;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use RuntimeException;

class DeliveryChargeService
{
    public function calculate(Address $address, string $tripType = 'two_way'): float
    {
        return $this->calculateDetails($address, $tripType)['delivery_charge'];
    }

    public function calculateDetails(Address $address, string $tripType = 'two_way'): array
    {
        $godownLatitude = config('services.google_maps.godown_latitude');
        $godownLongitude = config('services.google_maps.godown_longitude');

        if (!is_numeric($godownLatitude) || !is_numeric($godownLongitude)) {
            throw new InvalidArgumentException('Godown coordinates must be configured in system settings.');
        }

        if (!is_numeric($address->latitude) || !is_numeric($address->longitude)) {
            throw new InvalidArgumentException('The selected address does not have valid latitude and longitude.');
        }

        $distanceMeters = null;
        $apiKey = config('services.google_maps.api_key');

        // Tier 1: Try Google Maps Routes API if an API key is configured
        if (!empty($apiKey)) {
            try {
                $distanceMeters = $this->calculateWithGoogleMaps(
                    (float) $godownLatitude,
                    (float) $godownLongitude,
                    (float) $address->latitude,
                    (float) $address->longitude,
                    $apiKey
                );
            } catch (\Throwable $e) {
                Log::warning('Google Maps Routes API failed, falling back to OSRM: ' . $e->getMessage());
                $distanceMeters = null;
            }
        }

        // Tier 2: 100% Free OSRM (Open Source Routing Machine) driving road distance
        if ($distanceMeters === null) {
            try {
                $distanceMeters = $this->calculateWithOsrm(
                    (float) $godownLatitude,
                    (float) $godownLongitude,
                    (float) $address->latitude,
                    (float) $address->longitude
                );
            } catch (\Throwable $e) {
                Log::warning('OSRM route API failed, falling back to Haversine GPS formula: ' . $e->getMessage());
                $distanceMeters = null;
            }
        }

        // Tier 3: Local GPS Haversine formula with road winding factor (100% offline & zero failure)
        if ($distanceMeters === null) {
            $distanceMeters = $this->calculateWithHaversine(
                (float) $godownLatitude,
                (float) $godownLongitude,
                (float) $address->latitude,
                (float) $address->longitude
            );
        }

        $oneWayKilometers = round($distanceMeters / 1000, 2);
        $roundTripKilometers = round($oneWayKilometers * 2, 2);
        $ratePerKm = (float) config('services.google_maps.delivery_charge_per_km', 12);

        // One-way: distance * rate (e.g. 10 * 12 = 120)
        // Two-way: (distance + distance) * rate (e.g. (10 + 10) * 12 = 240)
        $chargeDistance = ($tripType === 'one_way') ? $oneWayKilometers : $roundTripKilometers;
        $deliveryCharge = round($chargeDistance * $ratePerKm);

        return [
            'trip_type' => $tripType,
            'distance_km' => $oneWayKilometers,
            'round_trip_km' => $roundTripKilometers,
            'delivery_charge' => (float) $deliveryCharge,
            'rate_per_km' => $ratePerKm,
        ];
    }

    private function calculateWithGoogleMaps(
        float $godownLat,
        float $godownLon,
        float $customerLat,
        float $customerLon,
        string $apiKey
    ): float {
        $response = Http::timeout(10)
            ->withHeaders([
                'X-Goog-Api-Key' => $apiKey,
                'X-Goog-FieldMask' => 'originIndex,destinationIndex,distanceMeters,status',
            ])
            ->post('https://routes.googleapis.com/distanceMatrix/v2:computeRouteMatrix', [
                'origins' => [[
                    'waypoint' => [
                        'location' => [
                            'latLng' => [
                                'latitude' => $godownLat,
                                'longitude' => $godownLon,
                            ],
                        ],
                    ],
                ]],
                'destinations' => [[
                    'waypoint' => [
                        'location' => [
                            'latLng' => [
                                'latitude' => $customerLat,
                                'longitude' => $customerLon,
                            ],
                        ],
                    ],
                ]],
                'travelMode' => 'DRIVE',
            ]);

        if (!$response->successful()) {
            throw new RuntimeException('Google Maps API returned error: ' . $response->status());
        }

        return $this->distanceMeters($response);
    }

    private function calculateWithOsrm(
        float $godownLat,
        float $godownLon,
        float $customerLat,
        float $customerLon
    ): ?float {
        // OSRM format: lon,lat;lon,lat
        $url = sprintf(
            'https://router.project-osrm.org/route/v1/driving/%F,%F;%F,%F?overview=false',
            $godownLon,
            $godownLat,
            $customerLon,
            $customerLat
        );

        $response = Http::timeout(5)->get($url);

        if ($response->successful()) {
            $data = $response->json();
            if (isset($data['routes'][0]['distance']) && is_numeric($data['routes'][0]['distance'])) {
                return (float) $data['routes'][0]['distance'];
            }
        }

        return null;
    }

    private function calculateWithHaversine(
        float $lat1,
        float $lon1,
        float $lat2,
        float $lon2
    ): float {
        $earthRadius = 6371000; // meters

        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($lonDelta / 2) * sin($lonDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $straightDistance = $earthRadius * $c;

        // Apply a 1.25x road factor to estimate realistic road route distance
        return $straightDistance * 1.25;
    }

    private function distanceMeters(Response $response): float
    {
        $body = trim($response->body());
        $payload = json_decode($body, true);

        if (is_array($payload) && isset($payload['distanceMeters']) && is_numeric($payload['distanceMeters'])) {
            return (float) $payload['distanceMeters'];
        }

        if (is_array($payload)) {
            foreach ($payload as $item) {
                if (is_array($item) && isset($item['distanceMeters']) && is_numeric($item['distanceMeters'])) {
                    return (float) $item['distanceMeters'];
                }
            }
        }

        foreach (preg_split('/\r?\n/', $body) as $line) {
            $item = json_decode(trim($line), true);
            if (is_array($item) && isset($item['distanceMeters']) && is_numeric($item['distanceMeters'])) {
                return (float) $item['distanceMeters'];
            }
        }

        throw new RuntimeException('Google Maps returned no delivery distance.');
    }
}
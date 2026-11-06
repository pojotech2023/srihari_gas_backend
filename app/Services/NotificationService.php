<?php

namespace App\Services;

use App\Models\Deliveryman;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Broadcast notification to all active deliverymen when a new order becomes available.
     * FCM Data payload: type = "available_order", order_id = "123"
     */
    public static function notifyAvailableOrder(Order $order): void
    {
        try {
            // Do not send if order is already assigned / claimed
            if ($order->deliveryman_id !== null) {
                Log::info("NotificationService: Order #ORD-{$order->id} is already assigned. Skipping available order notification.");
                return;
            }

            $deliverymen = Deliveryman::whereNotNull('fcm_token')
                ->where('fcm_token', '!=', '')
                ->get();

            $tokens = $deliverymen->pluck('fcm_token')->unique()->values()->all();

            if (empty($tokens)) {
                Log::info("NotificationService: No deliverymen with FCM token found for order #ORD-{$order->id}.");
                return;
            }

            $title = 'New Order Available';
            $body = 'A new order is available. Tap to claim it.';
            $data = [
                'type' => 'available_order',
                'order_id' => (string) $order->id,
            ];

            Log::info("NotificationService: Broadcasting available order #ORD-{$order->id} to " . count($tokens) . " deliverymen.");
            self::sendFcmNotification($tokens, $title, $body, $data);

        } catch (\Throwable $e) {
            Log::error('NotificationService::notifyAvailableOrder failed: ' . $e->getMessage());
        }
    }

    /**
     * Alias for notifyAvailableOrder for backwards compatibility.
     */
    public static function notifyAllDeliverymen(Order $order): void
    {
        self::notifyAvailableOrder($order);
    }

    /**
     * Send notification to a specific deliveryman when assigned by admin.
     */
    public static function notifyDeliverymanAssigned(Order $order, Deliveryman $deliveryman): void
    {
        try {
            Log::info("NotificationService: Order #ORD-{$order->id} assigned to Deliveryman #{$deliveryman->id} ({$deliveryman->name}).");

            if (empty($deliveryman->fcm_token)) {
                Log::warning("NotificationService: Deliveryman #{$deliveryman->id} has no FCM token registered.");
                return;
            }

            $title = 'Order Assigned';
            $body = 'Order assigned';
            $data = [
                'type' => 'order_assigned',
                'order_id' => (string) $order->id,
            ];

            self::sendFcmNotification([$deliveryman->fcm_token], $title, $body, $data);
        } catch (\Throwable $e) {
            Log::error('NotificationService::notifyDeliverymanAssigned failed: ' . $e->getMessage());
        }
    }

    /**
     * Send notification to customer when order is placed successfully.
     */
    public static function notifyCustomerOrderPlaced(Order $order): void
    {
        try {
            $customer = $order->user ?: User::find($order->user_id);
            if (!$customer || empty($customer->fcm_token)) {
                Log::info("NotificationService: Customer for order #ORD-{$order->id} has no FCM token registered.");
                return;
            }

            $title = 'Order Placed';
            $body = 'Your order placed successfully';
            $data = [
                'type' => 'order_placed',
                'order_id' => (string) $order->id,
            ];

            self::sendFcmNotification([$customer->fcm_token], $title, $body, $data);
        } catch (\Throwable $e) {
            Log::error('NotificationService::notifyCustomerOrderPlaced failed: ' . $e->getMessage());
        }
    }

    /**
     * Send notification to customer when order is delivered.
     */
    public static function notifyCustomerOrderDelivered(Order $order): void
    {
        try {
            $customer = $order->user ?: User::find($order->user_id);
            if (!$customer || empty($customer->fcm_token)) {
                Log::info("NotificationService: Customer for order #ORD-{$order->id} has no FCM token registered.");
                return;
            }

            $title = 'Order Delivered';
            $body = 'Delivered successfully';
            $data = [
                'type' => 'order_delivered',
                'order_id' => (string) $order->id,
            ];

            self::sendFcmNotification([$customer->fcm_token], $title, $body, $data);
        } catch (\Throwable $e) {
            Log::error('NotificationService::notifyCustomerOrderDelivered failed: ' . $e->getMessage());
        }
    }

    /**
     * Send notification to customer when payment is successful.
     */
    public static function notifyCustomerOrderPaid(Order $order): void
    {
        try {
            $customer = $order->user ?: User::find($order->user_id);
            if (!$customer || empty($customer->fcm_token)) {
                Log::info("NotificationService: Customer for order #ORD-{$order->id} has no FCM token registered.");
                return;
            }

            $title = 'Payment Successful';
            $body = 'Paid successfully';
            $data = [
                'type' => 'order_paid',
                'order_id' => (string) $order->id,
            ];

            self::sendFcmNotification([$customer->fcm_token], $title, $body, $data);
        } catch (\Throwable $e) {
            Log::error('NotificationService::notifyCustomerOrderPaid failed: ' . $e->getMessage());
        }
    }

    /**
     * Send notification to customer when payment fails.
     */
    public static function notifyCustomerOrderPaymentFailed(Order $order): void
    {
        try {
            $customer = $order->user ?: User::find($order->user_id);
            if (!$customer || empty($customer->fcm_token)) {
                Log::info("NotificationService: Customer for order #ORD-{$order->id} has no FCM token registered.");
                return;
            }

            $title = 'Payment Failed';
            $body = 'Payment failed for order #ORD-' . $order->id;
            $data = [
                'type' => 'order_payment_failed',
                'order_id' => (string) $order->id,
            ];

            self::sendFcmNotification([$customer->fcm_token], $title, $body, $data);
        } catch (\Throwable $e) {
            Log::error('NotificationService::notifyCustomerOrderPaymentFailed failed: ' . $e->getMessage());
        }
    }

    /**
     * Send notification to customer when order is cancelled.
     */
    public static function notifyCustomerOrderCancelled(Order $order): void
    {
        try {
            $customer = $order->user ?: User::find($order->user_id);
            if (!$customer || empty($customer->fcm_token)) {
                Log::info("NotificationService: Customer for order #ORD-{$order->id} has no FCM token registered.");
                return;
            }

            $title = 'Order Cancelled';
            $body = 'Your order cancel';
            $data = [
                'type' => 'order_cancelled',
                'order_id' => (string) $order->id,
            ];

            self::sendFcmNotification([$customer->fcm_token], $title, $body, $data);
        } catch (\Throwable $e) {
            Log::error('NotificationService::notifyCustomerOrderCancelled failed: ' . $e->getMessage());
        }
    }

    /**
     * Dispatch push notification via FCM HTTP v1 (or Legacy fallback).
     */
    public static function sendFcmNotification(array $tokens, string $title, string $body, array $data = []): void
    {
        $tokens = array_filter(array_unique($tokens));
        if (empty($tokens)) {
            return;
        }

        // Stringify all data values for FCM compatibility
        $stringData = [];
        foreach ($data as $key => $val) {
            $stringData[(string)$key] = (string)$val;
        }

        // Try FCM HTTP v1 first
        $accessToken = self::getFcmV1AccessToken();
        $projectId = config('services.fcm.project_id', 'gas-customer-45d72');

        if ($accessToken && $projectId) {
            $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

            foreach ($tokens as $token) {
                try {
                    $payload = [
                        'message' => [
                            'token' => $token,
                            'notification' => [
                                'title' => $title,
                                'body' => $body,
                            ],
                            'data' => $stringData,
                            'android' => [
                                'priority' => 'high',
                                'notification' => [
                                    'sound' => 'default',
                                    'channel_id' => 'high_importance_channel',
                                ],
                            ],
                            'apns' => [
                                'payload' => [
                                    'aps' => [
                                        'sound' => 'default',
                                    ],
                                ],
                            ],
                        ],
                    ];

                    $response = Http::timeout(5)
                        ->withHeaders([
                            'Authorization' => 'Bearer ' . $accessToken,
                            'Content-Type' => 'application/json',
                        ])
                        ->post($url, $payload);

                    Log::info("FCM v1 response for token {$token}: status={$response->status()}, body={$response->body()}");
                } catch (\Throwable $e) {
                    Log::error("FCM v1 dispatch error for token {$token}: " . $e->getMessage());
                }
            }
            return;
        }

        // Fallback: FCM Legacy API if server key set
        $serverKey = config('services.fcm.server_key');
        if (!empty($serverKey)) {
            try {
                foreach (array_chunk($tokens, 500) as $chunk) {
                    $payload = [
                        'registration_ids' => array_values($chunk),
                        'notification' => [
                            'title' => $title,
                            'body' => $body,
                            'sound' => 'default',
                        ],
                        'data' => $stringData,
                        'priority' => 'high',
                    ];

                    $response = Http::timeout(5)
                        ->withHeaders([
                            'Authorization' => 'key=' . $serverKey,
                            'Content-Type' => 'application/json',
                        ])
                        ->post('https://fcm.googleapis.com/fcm/send', $payload);

                    Log::info("FCM Legacy response: status={$response->status()}, body={$response->body()}");
                }
            } catch (\Throwable $e) {
                Log::error("FCM Legacy dispatch error: " . $e->getMessage());
            }
            return;
        }

        Log::info("NotificationService: Neither FCM v1 access token nor FCM_SERVER_KEY is configured. Simulated payload for tokens: " . json_encode($tokens));
    }

    /**
     * Generate OAuth2 Access Token using Service Account credentials JSON.
     */
    protected static function getFcmV1AccessToken(): ?string
    {
        $token = Cache::get('fcm_v1_oauth2_token');
        if ($token) {
            return $token;
        }

        $credentialsPath = config('services.fcm.credentials_path');
        if (!file_exists($credentialsPath)) {
            Log::warning("NotificationService: Firebase Credentials file not found at {$credentialsPath}");
            return null;
        }

        $credentials = json_decode(file_get_contents($credentialsPath), true);
        if (!$credentials || empty($credentials['private_key']) || empty($credentials['client_email'])) {
            Log::warning("NotificationService: Invalid credentials format in {$credentialsPath}");
            return null;
        }

        try {
            $now = time();
            $header = ['alg' => 'RS256', 'typ' => 'JWT'];
            $tokenUri = $credentials['token_uri'] ?? 'https://oauth2.googleapis.com/token';

            $payload = [
                'iss' => $credentials['client_email'],
                'sub' => $credentials['client_email'],
                'aud' => $tokenUri,
                'iat' => $now,
                'exp' => $now + 3600,
                'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            ];

            $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($header)));
            $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($payload)));
            $signatureInput = $base64UrlHeader . "." . $base64UrlPayload;

            $binarySignature = '';
            $success = openssl_sign($signatureInput, $binarySignature, $credentials['private_key'], 'SHA256');

            if (!$success) {
                Log::error("NotificationService: Failed to sign JWT assertion.");
                return null;
            }

            $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($binarySignature));
            $jwt = $signatureInput . "." . $base64UrlSignature;

            $response = Http::asForm()->post($tokenUri, [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt,
            ]);

            if ($response->successful() && $response->json('access_token')) {
                $accessToken = $response->json('access_token');
                $expiresIn = $response->json('expires_in', 3600);
                Cache::put('fcm_v1_oauth2_token', $accessToken, max(60, $expiresIn - 300));
                return $accessToken;
            }

            Log::error("NotificationService: Failed to get OAuth2 token. Status={$response->status()} Body={$response->body()}");
            return null;

        } catch (\Throwable $e) {
            Log::error("NotificationService: OAuth2 token generation exception: " . $e->getMessage());
            return null;
        }
    }
}

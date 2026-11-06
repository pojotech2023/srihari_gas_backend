<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Address;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Deliveryman;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CustomerApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('services.google_maps.api_key', 'test-google-key');
        config()->set('services.google_maps.godown_latitude', 12.9000);
        config()->set('services.google_maps.godown_longitude', 80.2000);
        config()->set('services.google_maps.delivery_charge_per_km', 12);

        Http::fake([
            'https://routes.googleapis.com/*' => Http::response([[
                'originIndex' => 0,
                'destinationIndex' => 0,
                'distanceMeters' => 10000,
            ]], 200),
        ]);
    }

    public function test_customer_signup_api()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'mobile_number' => '9876543211',
            'password' => 'password123',
            'confirm_password' => 'password123',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'user' => ['id', 'name', 'mobile_number'],
                    'token'
                ],
                'message'
            ]);

        $this->assertDatabaseHas('users', [
            'mobile_number' => '9876543211'
        ]);
    }

    public function test_customer_login_api()
    {
        $user = User::create([
            'name' => 'Jane Doe',
            'mobile_number' => '9876543212',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'mobile_number' => '9876543212',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'user',
                    'token'
                ]
            ]);
    }

    public function test_products_list_api()
    {
        $user = User::create([
            'name' => 'Jane Doe',
            'mobile_number' => '9876543212',
            'password' => bcrypt('password123'),
        ]);
        $token = $user->createToken('test')->plainTextToken;

        Product::create([
            'name' => 'Domestic LPG 14.2kg',
            'price' => 950.00,
            'description' => 'Standard domestic cylinder'
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/products');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment([
                'name' => 'Domestic LPG 14.2kg',
                'price' => '950.00'
            ]);
    }

    public function test_cart_apis()
    {
        $user = User::create([
            'name' => 'Jane Doe',
            'mobile_number' => '9876543212',
            'password' => bcrypt('password123'),
        ]);
        $token = $user->createToken('test')->plainTextToken;

        $product = Product::create([
            'name' => 'Domestic LPG 14.2kg',
            'price' => 950.00,
        ]);

        // Add to cart
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/cart', [
                'product_id' => $product->id,
                'quantity' => 2
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('cart_items', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 2
        ]);

        // List cart
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/cart');
        $response->assertStatus(200)->assertJsonCount(1, 'data');

        // Delete cart item
        $cartItemId = $response->json('data.0.id');
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->deleteJson('/api/cart/' . $cartItemId);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('cart_items', [
            'id' => $cartItemId
        ]);
    }

    public function test_delivery_address_apis()
    {
        $user = User::create([
            'name' => 'Jane Doe',
            'mobile_number' => '9876543212',
            'password' => bcrypt('password123'),
        ]);
        $token = $user->createToken('test')->plainTextToken;

        // Store address
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/addresses', [
                'address' => '123 Main Street',
                'location' => 'Velachery',
                'pincode' => '600042',
                'landmark' => 'Near Railway Station',
                'latitude' => 12.9756,
                'longitude' => 80.2212,
            ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('addresses', [
            'user_id' => $user->id,
            'location' => 'Velachery'
        ]);

        // List address
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/addresses');
        $response->assertStatus(200)->assertJsonCount(1, 'data');
    }

    public function test_checkout_and_callback_apis()
    {
        $user = User::create([
            'name' => 'Jane Doe',
            'mobile_number' => '9876543212',
            'password' => bcrypt('password123'),
        ]);
        $token = $user->createToken('test')->plainTextToken;

        $product = Product::create([
            'name' => 'Domestic LPG 14.2kg',
            'price' => 950.00,
        ]);

        $address = Address::create([
            'user_id' => $user->id,
            'address' => '123 Main Street',
            'location' => 'Velachery',
            'pincode' => '600042',
            'latitude' => 12.9756,
            'longitude' => 80.2212,
        ]);

        CartItem::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 1
        ]);

        // Checkout
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/checkout', [
                'address_id' => $address->id,
                'payment_method' => 'cod'
            ]);

        $response->assertStatus(201);
        $orderId = $response->json('data.id');

        $this->assertDatabaseHas('orders', [
            'id' => $orderId,
            'user_id' => $user->id,
            'total_price' => 1190.00,
            'status' => 'pending',
            'payment_status' => 'pending',
            'payment_method' => 'cod'
        ]);

        $this->assertEquals(240, $response->json('data.delivery_charge'));
        $this->assertEquals(10, $response->json('data.distance_km'));
        $this->assertEquals(20, $response->json('data.round_trip_km'));

        // Callback log verification
        $response = $this->postJson('/api/transactions/callback', [
            'order_id' => $orderId,
            'transaction_id' => 'tx_999998888',
            'amount' => 1190.00,
            'payment_gateway' => 'Razorpay',
            'status' => 'success',
            'payload' => ['payment_id' => 'pay_razor123', 'signature' => 'sig_abc']
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('transactions', [
            'order_id' => $orderId,
            'transaction_id' => 'tx_999998888',
            'status' => 'success'
        ]);

        $this->assertDatabaseHas('orders', [
            'id' => $orderId,
            'payment_status' => 'paid'
        ]);
    }

    public function test_online_checkout_payment_flow()
    {
        $user = User::create([
            'name' => 'Jane Doe',
            'mobile_number' => '9876543212',
            'password' => bcrypt('password123'),
        ]);
        $token = $user->createToken('test')->plainTextToken;

        $product = Product::create([
            'name' => 'Domestic LPG 14.2kg',
            'price' => 950.00,
        ]);

        $address = Address::create([
            'user_id' => $user->id,
            'address' => '123 Main Street',
            'location' => 'Velachery',
            'pincode' => '600042',
            'latitude' => 12.9756,
            'longitude' => 80.2212,
        ]);

        CartItem::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 1
        ]);

        // Checkout with online payment
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/checkout', [
                'address_id' => $address->id,
                'payment_method' => 'online',
                'razorpay_payment_id' => 'pay_razor_test123'
            ]);

        $response->assertStatus(201);
        $orderId = $response->json('data.id');

        $this->assertDatabaseHas('orders', [
            'id' => $orderId,
            'user_id' => $user->id,
            'payment_status' => 'paid',
            'payment_method' => 'online'
        ]);

        $this->assertDatabaseHas('transactions', [
            'order_id' => $orderId,
            'transaction_id' => 'pay_razor_test123',
            'payment_gateway' => 'Razorpay',
            'status' => 'success'
        ]);
    }

    public function test_customer_profile_update_api()
    {
        $user = User::create([
            'name' => 'Jane Doe',
            'mobile_number' => '9876543212',
            'password' => bcrypt('password123'),
        ]);
        $token = $user->createToken('test')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/profile/update', [
                'name' => 'Jane Smith',
                'mobile_number' => '9876543215',
                'password' => 'newpassword123'
            ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'Jane Smith',
                'mobile_number' => '9876543215'
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Jane Smith',
            'mobile_number' => '9876543215'
        ]);
    }

    public function test_deliveryman_apis()
    {
        // Create deliveryman
        $driver = \App\Models\Deliveryman::create([
            'name' => 'Ramesh Driver',
            'mobile_number' => '9876543299',
            'password' => bcrypt('driverpwd123')
        ]);

        // Login Deliveryman
        $response = $this->postJson('/api/deliveryman/login', [
            'mobile_number' => '9876543299',
            'password' => 'driverpwd123'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'deliveryman',
                    'token'
                ]
            ]);

        $driverToken = $response->json('data.token');

        // Create an assigned order
        $customer = User::create([
            'name' => 'Alice Customer',
            'mobile_number' => '9876543233',
            'password' => bcrypt('password123'),
        ]);
        $address = Address::create([
            'user_id' => $customer->id,
            'address' => '45 Green St',
            'location' => 'Adyar',
            'pincode' => '600020',
            'latitude' => 13.0067,
            'longitude' => 80.2572,
        ]);
        $order = Order::create([
            'user_id' => $customer->id,
            'address_id' => $address->id,
            'deliveryman_id' => $driver->id,
            'status' => 'assigned',
            'total_price' => 950.00,
            'payment_status' => 'pending',
            'payment_method' => 'cod'
        ]);

        // Get assigned orders list
        $response = $this->withHeader('Authorization', 'Bearer ' . $driverToken)
            ->getJson('/api/deliveryman/orders');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');

        // Get specific order detail
        $response = $this->withHeader('Authorization', 'Bearer ' . $driverToken)
            ->getJson('/api/deliveryman/orders/' . $order->id);

        $response->assertStatus(200)
            ->assertJsonPath('data.user.name', 'Alice Customer')
            ->assertJsonPath('data.address.location', 'Adyar')
            ->assertJsonPath('data.delivery_charge', 0);

        // Update status to "in progress"
        $response = $this->withHeader('Authorization', 'Bearer ' . $driverToken)
            ->postJson('/api/deliveryman/orders/' . $order->id . '/status', [
                'status' => 'in progress'
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'in progress'
        ]);

        // Update status to "delivered"
        $response = $this->withHeader('Authorization', 'Bearer ' . $driverToken)
            ->postJson('/api/deliveryman/orders/' . $order->id . '/status', [
                'status' => 'delivered'
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'delivered',
            'payment_status' => 'paid'
        ]);
    }

    public function test_checkout_calculates_delivery_charge_without_google_api_key()
    {
        // Disable Google API key to test OSRM / Haversine fallback
        config()->set('services.google_maps.api_key', null);

        Http::fake([
            'https://router.project-osrm.org/*' => Http::response([
                'code' => 'Ok',
                'routes' => [[
                    'distance' => 5000, // 5 KM one way => 10 KM round trip => 10 * 12 = Rs.120
                ]],
            ], 200),
        ]);

        $user = User::create([
            'name' => 'Test Fallback User',
            'mobile_number' => '9876543299',
            'password' => bcrypt('password123'),
        ]);
        $token = $user->createToken('test')->plainTextToken;

        $product = Product::create([
            'name' => 'Commercial LPG 19kg',
            'price' => 1800.00,
        ]);

        $address = Address::create([
            'user_id' => $user->id,
            'address' => 'Main Road, Arimalam',
            'location' => 'Pudukkottai',
            'pincode' => '622201',
            'latitude' => 10.2700,
            'longitude' => 78.9000,
        ]);

        CartItem::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/checkout', [
                'address_id' => $address->id,
                'payment_method' => 'cod',
            ]);

        $response->assertStatus(201);
        // Round trip: 5 KM * 2 = 10 KM * 12 = 120.00
        $this->assertEquals(120.00, $response->json('data.delivery_charge'));
        $this->assertEquals(5.00, $response->json('data.distance_km'));
        $this->assertEquals(10.00, $response->json('data.round_trip_km'));
        $this->assertEquals(1920.00, $response->json('data.total_price'));
    }

    public function test_one_way_product_calculates_single_trip_delivery_charge()
    {
        $user = User::create([
            'name' => 'One Way User',
            'mobile_number' => '9876543111',
            'password' => bcrypt('password123'),
        ]);
        $token = $user->createToken('test')->plainTextToken;

        // Product with one_way delivery
        $product = Product::create([
            'name' => 'One Way Pipe Accessory',
            'price' => 500.00,
            'trip_type' => 'one_way',
        ]);

        $address = Address::create([
            'user_id' => $user->id,
            'address' => 'One Way Lane',
            'location' => 'Velachery',
            'pincode' => '600042',
            'latitude' => 12.9756,
            'longitude' => 80.2212,
        ]);

        CartItem::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/checkout', [
                'address_id' => $address->id,
                'payment_method' => 'cod',
            ]);

        $response->assertStatus(201);
        // Distance is 10 KM (mocked). One way charge = 10 * 12 = 120
        $this->assertEquals(120, $response->json('data.delivery_charge'));
        $this->assertEquals(620, $response->json('data.total_price'));
        $this->assertEquals('one_way', $response->json('data.trip_type'));
    }

    public function test_deliveryman_available_orders_and_claim_flow()
    {
        $driver = Deliveryman::create([
            'name' => 'Driver Arjun',
            'mobile_number' => '9898989801',
            'password' => bcrypt('password123'),
        ]);
        $driverToken = $driver->createToken('deliveryman_token')->plainTextToken;

        $driver2 = Deliveryman::create([
            'name' => 'Driver Bala',
            'mobile_number' => '9898989802',
            'password' => bcrypt('password123'),
        ]);
        $driverToken2 = $driver2->createToken('deliveryman_token')->plainTextToken;

        $customer = User::create([
            'name' => 'Order Owner',
            'mobile_number' => '9876543000',
            'password' => bcrypt('password123'),
        ]);
        $address = Address::create([
            'user_id' => $customer->id,
            'address' => '100 Beach Road',
            'location' => 'Marina',
            'pincode' => '600004',
            'latitude' => 13.0400,
            'longitude' => 80.2800,
        ]);

        $order = Order::create([
            'user_id' => $customer->id,
            'address_id' => $address->id,
            'deliveryman_id' => null,
            'status' => 'pending',
            'total_price' => 1000.00,
            'payment_status' => 'pending',
            'payment_method' => 'cod',
        ]);

        // 1. List available orders
        $response = $this->withHeader('Authorization', 'Bearer ' . $driverToken)
            ->getJson('/api/deliveryman/available-orders');

        $response->assertStatus(200)
            ->assertJsonPath('data.0.id', $order->id);

        // 2. Driver 1 claims the order
        $response = $this->withHeader('Authorization', 'Bearer ' . $driverToken)
            ->postJson('/api/deliveryman/orders/' . $order->id . '/claim');

        $response->assertStatus(200);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'deliveryman_id' => $driver->id,
            'status' => 'assigned',
        ]);

        // 3. Driver 2 tries to claim the same order -> gets 409 Conflict
        $response = $this->withHeader('Authorization', 'Bearer ' . $driverToken2)
            ->postJson('/api/deliveryman/orders/' . $order->id . '/claim');

        $response->assertStatus(409);
    }

    public function test_deliveryman_update_payment_status_endpoint()
    {
        $driver = Deliveryman::create([
            'name' => 'Driver Suresh',
            'mobile_number' => '9898989803',
            'password' => bcrypt('password123'),
        ]);
        $driverToken = $driver->createToken('deliveryman_token')->plainTextToken;

        $customer = User::create([
            'name' => 'Customer Suresh',
            'mobile_number' => '9876543001',
            'password' => bcrypt('password123'),
        ]);
        $address = Address::create([
            'user_id' => $customer->id,
            'address' => '200 High Road',
            'location' => 'Guindy',
            'pincode' => '600032',
            'latitude' => 13.0067,
            'longitude' => 80.2000,
        ]);

        $order = Order::create([
            'user_id' => $customer->id,
            'address_id' => $address->id,
            'deliveryman_id' => $driver->id,
            'status' => 'assigned',
            'total_price' => 1000.00,
            'payment_status' => 'pending',
            'payment_method' => 'cod',
        ]);

        // Flutter team endpoint: POST /api/deliveryman/orders/{order_id}/payment-status with {"payment_status": "paid"}
        $response = $this->withHeader('Authorization', 'Bearer ' . $driverToken)
            ->postJson('/api/deliveryman/orders/' . $order->id . '/payment-status', [
                'payment_status' => 'paid',
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'payment_status' => 'paid',
        ]);
    }

    public function test_deliveryman_fcm_token_and_document_mapping()
    {
        $driver = Deliveryman::create([
            'name' => 'Driver Document Test',
            'mobile_number' => '9898989899',
            'password' => bcrypt('password123'),
        ]);
        $driverToken = $driver->createToken('deliveryman_token')->plainTextToken;

        // 1. Update FCM Token
        $response = $this->withHeader('Authorization', 'Bearer ' . $driverToken)
            ->postJson('/api/deliveryman/fcm-token', [
                'fcm_token' => 'fcm_dummy_token_abcdef123456',
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('deliverymen', [
            'id' => $driver->id,
            'fcm_token' => 'fcm_dummy_token_abcdef123456',
        ]);
    }
}

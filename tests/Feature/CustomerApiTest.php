<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Address;
use App\Models\CartItem;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerApiTest extends TestCase
{
    use RefreshDatabase;

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
                'landmark' => 'Near Railway Station'
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
            'pincode' => '600042'
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
            'total_price' => 950.00,
            'status' => 'pending',
            'payment_status' => 'pending',
            'payment_method' => 'cod'
        ]);

        // Callback log verification
        $response = $this->postJson('/api/transactions/callback', [
            'order_id' => $orderId,
            'transaction_id' => 'tx_999998888',
            'amount' => 950.00,
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
            'payment_status' => 'completed'
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
            'pincode' => '600042'
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
            'payment_status' => 'completed',
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
            'pincode' => '600020'
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
            ->assertJsonPath('data.address.location', 'Adyar');

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
            'payment_status' => 'completed'
        ]);
    }
}

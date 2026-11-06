<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Deliveryman;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_check_mobile_success_and_failure()
    {
        User::create([
            'name' => 'Test User',
            'mobile_number' => '9876543210',
            'password' => Hash::make('password123'),
        ]);

        // Success case
        $response = $this->postJson('/api/check-mobile', [
            'mobile_number' => '9876543210',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Customer mobile number verified successfully.',
            ]);

        // Failure case
        $responseFail = $this->postJson('/api/check-mobile', [
            'mobile_number' => '9999999999',
        ]);

        $responseFail->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Customer account not found with this mobile number.',
            ]);
    }

    public function test_customer_forgot_password_success()
    {
        $user = User::create([
            'name' => 'Test User',
            'mobile_number' => '9876543210',
            'password' => Hash::make('oldpassword123'),
        ]);

        $response = $this->postJson('/api/forgot-password', [
            'mobile_number' => '9876543210',
            'password' => 'newpassword123',
            'confirm_password' => 'newpassword123',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Password reset successfully. Please login with your new password.',
            ]);

        $user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $user->password));

        // Test login with new password
        $loginResponse = $this->postJson('/api/login', [
            'mobile_number' => '9876543210',
            'password' => 'newpassword123',
        ]);

        $loginResponse->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Login successful',
            ]);
    }

    public function test_customer_forgot_password_supports_new_password_and_password_confirmation_keys()
    {
        $user = User::create([
            'name' => 'Test User',
            'mobile_number' => '9876543210',
            'password' => Hash::make('oldpassword123'),
        ]);

        $response = $this->postJson('/api/forgot-password', [
            'mobile_number' => '9876543210',
            'new_password' => 'flutterpass123',
            'password_confirmation' => 'flutterpass123',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $user->refresh();
        $this->assertTrue(Hash::check('flutterpass123', $user->password));
    }

    public function test_customer_forgot_password_validation_error()
    {
        $response = $this->postJson('/api/forgot-password', [
            'mobile_number' => '9876543210',
            'password' => 'short',
            'confirm_password' => 'mismatch',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Validation error',
            ]);
    }

    public function test_customer_forgot_password_user_not_found()
    {
        $response = $this->postJson('/api/forgot-password', [
            'mobile_number' => '0000000000',
            'password' => 'newpassword123',
            'confirm_password' => 'newpassword123',
        ]);

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Customer account not found with this mobile number.',
            ]);
    }

    public function test_deliveryman_check_mobile_success_and_failure()
    {
        Deliveryman::create([
            'name' => 'Agent Ramesh',
            'mobile_number' => '8888888888',
            'password' => Hash::make('oldagentpass'),
        ]);

        // Success case
        $response = $this->postJson('/api/deliveryman/check-mobile', [
            'mobile_number' => '8888888888',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Delivery partner mobile number verified successfully.',
            ]);

        // Failure case
        $responseFail = $this->postJson('/api/deliveryman/check-mobile', [
            'mobile_number' => '7777777777',
        ]);

        $responseFail->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Delivery partner account not found with this mobile number.',
            ]);
    }

    public function test_deliveryman_forgot_password_success()
    {
        $deliveryman = Deliveryman::create([
            'name' => 'Agent Ramesh',
            'mobile_number' => '8888888888',
            'password' => Hash::make('oldagentpass'),
        ]);

        $response = $this->postJson('/api/deliveryman/forgot-password', [
            'mobile_number' => '8888888888',
            'password' => 'newagentpass123',
            'confirm_password' => 'newagentpass123',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Password reset successfully. Please login with your new password.',
            ]);

        $deliveryman->refresh();
        $this->assertTrue(Hash::check('newagentpass123', $deliveryman->password));

        // Test login with new password
        $loginResponse = $this->postJson('/api/deliveryman/login', [
            'mobile_number' => '8888888888',
            'password' => 'newagentpass123',
        ]);

        $loginResponse->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Login successful',
            ]);
    }

    public function test_deliveryman_forgot_password_supports_new_password_and_password_confirmation_keys()
    {
        $deliveryman = Deliveryman::create([
            'name' => 'Agent Ramesh',
            'mobile_number' => '8888888888',
            'password' => Hash::make('oldagentpass'),
        ]);

        $response = $this->postJson('/api/deliveryman/forgot-password', [
            'mobile_number' => '8888888888',
            'new_password' => 'agentflutterpass123',
            'password_confirmation' => 'agentflutterpass123',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $deliveryman->refresh();
        $this->assertTrue(Hash::check('agentflutterpass123', $deliveryman->password));
    }
}

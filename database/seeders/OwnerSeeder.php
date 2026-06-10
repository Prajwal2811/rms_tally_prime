<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Owner;
use Illuminate\Support\Facades\Hash;

class OwnerSeeder extends Seeder
{
    public function run(): void
    {
        Owner::create([
            'owner_name' => 'Test Owner',
            'email' => 'owner@test.com',
            'phone' => '9999999999',
            'business_name' => 'Test Business',
            'business_type' => 'shop',
            'address' => 'Pune',
            'password' => Hash::make('12345678'),
            'pass' => '12345678',
            'status' => 'active',
            'is_subscribed' => "false",
            'subscription_expiry' => null,
        ]);
    }
}
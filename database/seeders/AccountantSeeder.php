<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Accountant;
use Illuminate\Support\Facades\Hash;

class AccountantSeeder extends Seeder
{
    public function run(): void
    {
        $accountants = [
            [
                'name' => 'Admin Accountant',
                'email' => 'accountant1@test.com',
                'phone' => '9876543210',
            ],
            [
                'name' => 'Rahul Sharma',
                'email' => 'accountant2@test.com',
                'phone' => '9876543211',
            ],
            [
                'name' => 'Priya Verma',
                'email' => 'accountant3@test.com',
                'phone' => '9876543212',
            ],
            [
                'name' => 'Amit Patel',
                'email' => 'accountant4@test.com',
                'phone' => '9876543213',
            ],
            [
                'name' => 'Sneha Joshi',
                'email' => 'accountant5@test.com',
                'phone' => '9876543214',
            ],
            [
                'name' => 'Vikram Singh',
                'email' => 'accountant6@test.com',
                'phone' => '9876543215',
            ],
            [
                'name' => 'Pooja Gupta',
                'email' => 'accountant7@test.com',
                'phone' => '9876543216',
            ],
            [
                'name' => 'Karan Mehta',
                'email' => 'accountant8@test.com',
                'phone' => '9876543217',
            ],
            [
                'name' => 'Neha Kulkarni',
                'email' => 'accountant9@test.com',
                'phone' => '9876543218',
            ],
            [
                'name' => 'Rohit Deshmukh',
                'email' => 'accountant10@test.com',
                'phone' => '9876543219',
            ],
        ];

        foreach ($accountants as $accountant) {
            Accountant::create([
                'name' => $accountant['name'],
                'email' => $accountant['email'],
                'phone' => $accountant['phone'],
                'address' => 'Nagpur, Maharashtra',
                'password' => Hash::make('12345678'),
                'pass' => '12345678',
                'status' => 'active',
            ]);
        }
    }
}
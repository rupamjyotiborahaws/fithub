<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MembershipTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('memberships')->insert([
            [
                'type' => 'Starter',
                'price' => 2000.00,
                'duration_months' => 1,
                'benefits' => 'Access to gym equipment, 1 group class per week',
                'description' => 'Starter membership for first time gym users',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'type' => 'Quarterly (3 months)',
                'price' => 5000.00,
                'duration_months' => 3,
                'benefits' => 'Access to gym equipment, 3 group classes per week, 1 personal training session per month',
                'description' => 'Quarterly membership for regular users',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'type' => 'Half Yearly (6 months)',
                'price' => 8000.00,
                'duration_months' => 6,
                'benefits' => 'Access to gym equipment, 3 group classes per week, 1 personal training session per month',
                'description' => 'Half Yearly membership for regular users',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'type' => 'Yearly (12 months)',
                'price' => 14000.00,
                'duration_months' => 12,
                'benefits' => 'Unlimited access to gym equipment and group classes, 2 personal training sessions per month, access to sauna and pool',
                'description' => 'Yearly membership for fitness enthusiasts',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}

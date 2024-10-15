<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // branch create
        Branch::create(
            [
                'code' => 'B00001',
                'name' => 'Main Branch',
                'title' => 'Main Branch',
                'phone' => '017########',
                'created_by' => 1,
                'ipAddress' => request()->ip()
            ],
            [
                'code' => 'B00002',
                'name' => 'Second Branch',
                'title' => 'Second Branch',
                'phone' => '017########',
                'created_by' => 1,
                'ipAddress' => request()->ip()
            ]
        );

        User::create([
            'code' => 'U00001',
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make(1),
            'phone' => '019########',
            'role' => 'Superadmin',
            'branch_id' => 1,
            'ipAddress' => request()->ip()
        ]);
    }
}

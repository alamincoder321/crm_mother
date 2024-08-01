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
        // organization create
        Branch::create([
            'code' => 'OR00001',
            'name' => 'Big Technology Ltd.',
            'title' => 'Big Technology Ltd.',
            'phone' => '017########',
            'added_by' => 1,
            'ip_address' => request()->ip()
        ]);
        Branch::create([
            'code' => 'OR00002',
            'name' => 'Second Organization',
            'title' => 'Second Organization',
            'phone' => '017########',
            'added_by' => 1,
            'ip_address' => request()->ip()
        ]);

        User::create([
            'code' => 'U00001',
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make(1),
            'phone' => '019########',
            'role' => 'Superadmin',
            'branch_id' => 1,
            'ip_address' => request()->ip()
        ]);
    }
}

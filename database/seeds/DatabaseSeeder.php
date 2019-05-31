<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@test.com',
            'password' => bcrypt('123456'),
            'role' => 'superadmin'
        ]);
        User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('123456'),
            'role' => 'admin'
        ]);
        User::create([
            'name' => 'User',
            'email' => 'user@test.com',
            'password' => bcrypt('123456'),
            'role' => 'user'
        ]);
    }
}

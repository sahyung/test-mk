<?php

use App\Models\User;
use App\Models\Kost;
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
        // table users
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
            'name' => 'User Premium',
            'email' => 'userp@test.com',
            'credit' => 40,
            'is_premium' => true,
            'password' => bcrypt('123456'),
            'role' => 'user'
        ]);
        User::create([
            'name' => 'User Biasa',
            'email' => 'userb@test.com',
            'credit' => 20,
            'password' => bcrypt('123456'),
            'role' => 'user'
        ]);
        User::create([
            'name' => 'Kost Owner',
            'email' => 'kost@test.com',
            'password' => bcrypt('123456'),
            'role' => 'kostowner'
        ]);
        User::create([
            'name' => 'Kost Owner',
            'email' => 'kost1@test.com',
            'password' => bcrypt('123456'),
            'role' => 'kostowner'
        ]);
        User::create([
            'name' => 'Kost Owner',
            'email' => 'kost2@test.com',
            'password' => bcrypt('123456'),
            'role' => 'kostowner'
        ]);

        // table kosts
        foreach (User::all() as $key => $user) {
            if ($user->role == 'kostowner') {
                Kost::create([
                    'user_id' => $user->id,
                    'name' => 'Kost dummy '.$key,
                    'city' => 'yogyakarta',
                    'price' => 5000000,
                    'available_room_count' => 1,
                    'total_room_count' => 1,
                ]);

                Kost::create([
                    'user_id' => $user->id,
                    'name' => 'Kost dummy '.$key,
                    'city' => 'surabaya',
                    'price' => 1000000,
                    'available_room_count' => 1,
                    'total_room_count' => 1,
                ]);

                Kost::create([
                    'user_id' => $user->id,
                    'name' => 'Kost dummy '.$key,
                    'city' => 'magelang',
                    'price' => 750000,
                    'available_room_count' => 1,
                    'total_room_count' => 1,
                ]);
            }
        }
    }
}

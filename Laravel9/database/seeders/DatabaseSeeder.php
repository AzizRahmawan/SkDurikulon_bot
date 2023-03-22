<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

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
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $user = [
            [
               'name'=>'Admin',
               'email'=>'admin@gmail.com',
                'job'=>'admin',
               'password'=> bcrypt('123456'),
            ],
            [
               'name'=>'Super Admin',
               'email'=>'superadmin@gmail.com',
                'job'=>'super_admin',
               'password'=> bcrypt('123456'),
            ],
        ];

        foreach ($user as $key => $value) {
            User::create($value);
        }
    }
}

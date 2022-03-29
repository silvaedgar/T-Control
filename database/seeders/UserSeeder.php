<?php
namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('users')->insert([
        //     'name' => 'Admin Admin',
        //     'email' => 'admin@material.com',
        //     'email_verified_at' => now(),
        //     'password' => Hash::make('secret'),
        //     'created_at' => now(),
        //     'updated_at' => now()
        // ]);   Creacion de Creative Tim o del them

        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('12345678'),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ])->assignRole('Admin');

        User::create([
            'name' => 'User',
            'email' => 'user@gmail.com',
            'password' => bcrypt('12345678'),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ])->assignRole('User');

    }
}

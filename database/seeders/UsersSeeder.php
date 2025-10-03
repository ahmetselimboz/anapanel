<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       User::updateOrCreate([
            'email' => "cihanxdogan@gmail.com",
       ], [
            'name' => "Cihan Doğan",
            'password' => Hash::make('123123Qw.#'),
            'status' => 1,
            'created_at' => now(),
            'avatar' => 'backend/assets/icons/avatar.png'
        ]);
    }
}

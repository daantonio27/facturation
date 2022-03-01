<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SuperAdminUser extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Mbaye Sokhna THIAM',
            'email' => 'mbayesokhnathiam@gmail.com',
            'users_role_id' => 1,
            'email_verified_at' => now(),
            'password' => Hash::make('P@sser12'),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}

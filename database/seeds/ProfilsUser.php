<?php

use App\Role;
use Illuminate\Database\Seeder;

class ProfilsUser extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'id' => 1,
            'name' => 'SUPER-ADMIN'
        ]);

        Role::create([
            'id' => 2,
            'name' => 'ADMIN'
        ]);

        Role::create([
            'id' => 3,
            'name' => 'GESTIONNAIRE STOCK'
        ]);

        Role::create([
            'id' => 4,
            'name' => 'CAISSIER(e)'
        ]);

        Role::create([
            'id' => 5,
            'name' => 'GESTIONNAIRE DOCUMENTS'
        ]);
    }
}

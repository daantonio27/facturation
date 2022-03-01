<?php

use Illuminate\Database\Seeder;

class DefaultUnits extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Unite::create([
            'id' => 1,
            'name' => ''
        ]);

        Unite::create([
            'id' => 2,
            'name' => 'u'
        ]);

        Unite::create([
            'id' => 3,
            'name' => 'm²'
        ]);

        Unite::create([
            'id' => 4,
            'name' => 'mètre'
        ]);

        Unite::create([
            'id' => 5,
            'name' => 'litre(s)'
        ]);

        Unite::create([
            'id' => 6,
            'name' => 'carton(s)'
        ]);

        Unite::create([
            'id' => 7,
            'name' => 'paquet(s)'
        ]);
    }
}

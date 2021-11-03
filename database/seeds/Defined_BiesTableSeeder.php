<?php

use Illuminate\Database\Seeder;

class Defined_BiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('define_bies')->insert([[
            'id_query'=>1,
            'id_user'=>1,
            'updated_at' => date('Y-m-d h:i:s'),
            'created_at' => date('Y-m-d h:i:s')
            
        ],[

            'id_query'=>2,
            'id_user'=>1,
            'updated_at' => date('Y-m-d h:i:s'),
            'created_at' => date('Y-m-d h:i:s')

        ]]);
    }
}

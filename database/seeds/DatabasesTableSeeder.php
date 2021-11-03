<?php

use Illuminate\Database\Seeder;

class DatabasesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('databases')->insert([[
            'name' => 'PubMed',
            'webpage' => 'https://pubmed.ncbi.nlm.nih.gov/',
            'updated_at' => date('Y-m-d h:i:s'),
            'created_at' => date('Y-m-d h:i:s')
            
        ],[

            'name' => 'Scopus',
            'webpage' => 'https://www.scopus.com/home.uri',
            'updated_at' => date('Y-m-d h:i:s'),
            'created_at' => date('Y-m-d h:i:s')

        ]]);
    }
}

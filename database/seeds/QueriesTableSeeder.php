<?php

use Illuminate\Database\Seeder;

class QueriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('queries')->insert([[
            'name' => 'Query 1',
            'description_long'=>null,
            'description_short'=>'prototipazione ui',
            // 'creation_date' => date('Y-m-d h:i:s'),
            'pre_exc' => '0',
            'apiKey'=>'25a9d91d9b88484630d8745e96a85583c408',
            'latest_exc_date' =>null,
            'ret_start'=>0,
            'ret_max'=>null,
            'exec_in_progress'=>0,
            'train_in_progress'=>0,
            'seed'=>0,
            'accuracy' => 60,
            // 'querydate'=> date('Y-m-d h:i:s'),
            'place'=>null,
            'key_phrases'=>'asd',
            'string'=>'covid',
            'updated_at' => date('Y-m-d h:i:s'),
            'created_at' => date('Y-m-d h:i:s')
            
        ],[

            'name' => 'Query 2',
            'description_long'=>null,
            'description_short'=>null,
            'apiKey'=>'25a9d91d9b88484630d8745e96a85583c408',
            // 'creation_date' => date('Y-m-d h:i:s'),
            'pre_exc' =>'0',
            'latest_exc_date' =>null,
            'ret_start'=>1,
            'ret_max'=>null,
            'exec_in_progress'=>0,
            'train_in_progress'=>0,
            'seed'=>0,
            'accuracy' => null,
            // 'querydate'=> date('Y-m-d h:i:s'),
            'place'=>null,
            'key_phrases'=>'asd',
            'string'=>'Vitamine D',
            'updated_at' => date('Y-m-d h:i:s'),
            'created_at' => date('Y-m-d h:i:s')

        ]]);
    }
}

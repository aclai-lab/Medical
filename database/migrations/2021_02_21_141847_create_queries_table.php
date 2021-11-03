<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQueriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('queries', function (Blueprint $table) {
            $table->id();
            $table->string('name');//unique sarebbe da rimuovere 
            // $table->string('project_type')->unique();//??

            $table->string('description_short')->nullable();
            $table->string('description_long')->nullable();
            // $table->string('apiKey');


            // $table->date('creationdate');
            $table->date('latest_exc_date')->nullable();
            $table->integer('ret_start');

            $table->string('pre_exc');

            // $table->integer('completion')->nullable();
            $table->integer('ret_max')->nullable();
            $table->integer('exec_in_progress')->nullable();
            $table->integer('train_in_progress')->nullable();
            $table->integer('seed')->nullable(); 
            $table->float('accuracy')->nullable();


            // $table->date('querydate');
            $table->string('place')->nullable();
            $table->string('matrix')->nullable();
            $table->string('key_phrases')->nullable();
            $table->string('string');//unique sarebbe da rimuovere possono essitere stringhe duplicate 

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('queries');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accesses', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('id_user')->unsigned();
            $table->bigInteger('id_database')->unsigned();
          

            $table->string('user_name')->nullable();
            $table->string('db_password')->nullable();
            $table->string('api_key')->nullable();
            // $table->date('start_date')->nullable();
            // $table->date('end_date')->nullable();

            $table->foreign('id_user')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_database')->references('id')->on('databases')->onUpdate('cascade')->onDelete('cascade');


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
        Schema::dropIfExists('accesses');
    }
}

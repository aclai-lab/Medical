<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFindsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('finds', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('id_query')->unsigned();
            $table->bigInteger('id_affialition')->unsigned();

            $table->foreign('id_query')->references('id')->on('queries')->onUpdate('cascade')->onDelete('cascade');;
            $table->foreign('id_affialition')->references('id')->on('affiliations')->onUpdate('cascade')->onDelete('cascade');;

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
        Schema::dropIfExists('finds');
    }
}

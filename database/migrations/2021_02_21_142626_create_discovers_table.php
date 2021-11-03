<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscoversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discovers', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('id_query')->unsigned();
            $table->bigInteger('id_pubblication')->unsigned();

            $table->string('source_db');

            $table->foreign('id_query')->references('id')->on('queries')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_pubblication')->references('id')->on('pubblications')->onUpdate('cascade')->onDelete('cascade');



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
        Schema::dropIfExists('discovers');
    }
}

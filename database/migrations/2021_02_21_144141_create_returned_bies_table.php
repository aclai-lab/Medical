<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturnedBiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('returned_bies', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('id_outcome')->unsigned();
            $table->bigInteger('id_query')->unsigned();

            $table->foreign('id_outcome')->references('id')->on('outcomes')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_query')->references('id')->on('queries')->onUpdate('cascade')->onDelete('cascade');

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
        Schema::dropIfExists('returned_bies');
    }
}

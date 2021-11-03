<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeasuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('measures', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('id_metric')->unsigned();
            $table->bigInteger('id_author')->unsigned();

            $table->string('mesure_year');
            $table->integer('mesure_value');

            $table->foreign('id_metric')->references('id')->on('metrics')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_author')->references('id')->on('authors')->onUpdate('cascade')->onDelete('cascade');


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
        Schema::dropIfExists('measures');
    }
}

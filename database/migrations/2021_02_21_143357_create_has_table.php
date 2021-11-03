<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('has', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('id_author')->unsigned();
            $table->bigInteger('id_affiliation')->unsigned();
            $table->string('has_year')->nullable();

            $table->foreign('id_author')->references('id')->on('authors')->onUpdate('cascade')->onDelete('cascade');;
            $table->foreign('id_affiliation')->references('id')->on('affiliations')->onUpdate('cascade')->onDelete('cascade');;


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
        Schema::dropIfExists('has');
    }
}

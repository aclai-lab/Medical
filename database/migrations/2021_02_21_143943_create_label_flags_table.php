<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLabelFlagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('label_flags', function (Blueprint $table) {
            $table->id();
            // $table->string('name')->unique();
            $table->bigInteger('id_query')->unsigned();
            $table->string('name');

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
        Schema::dropIfExists('label_flags');
    }
}

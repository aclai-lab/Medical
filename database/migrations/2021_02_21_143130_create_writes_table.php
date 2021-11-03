<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWritesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('writes', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('id_pubblication')->unsigned();
            $table->bigInteger('id_author')->unsigned();
            $table->integer('author_number');

            $table->foreign('id_pubblication')->references('id')->on('pubblications')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('writes');
    }
}

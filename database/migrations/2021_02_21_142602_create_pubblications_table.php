<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePubblicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pubblications', function (Blueprint $table) {
            $table->id();

            $table->longText('title')->nullable();
            $table->longText('abstract')->nullable();
            $table->string('pubblication_year')->nullable();
            $table->string('pages')->nullable();





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
        Schema::dropIfExists('pubblications');
    }
}

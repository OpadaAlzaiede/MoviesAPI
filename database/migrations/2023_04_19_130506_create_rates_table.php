<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rates', function (Blueprint $table) {
            $table->id();
            $table->double('rate');
            $table->dateTime('date');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('movie_id');

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('movie_id')->references('id')->on('movies');

            $table->index(['user_id', 'movie_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rates');
    }
}

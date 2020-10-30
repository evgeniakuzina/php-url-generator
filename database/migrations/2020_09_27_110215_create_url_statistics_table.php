<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUrlStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('url_statistics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('url_id');
            $table->timestamp('date_time');
            $table->string('user_agent');

            $table->foreign('url_id')->references('id')->on('urls')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('url_statistics');
    }
}

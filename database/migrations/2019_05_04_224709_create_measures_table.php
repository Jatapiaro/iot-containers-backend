<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->bigIncrements('id');

            $table->double('height', 30, 4); // Height from the sensor to the water
            $table->double('volume', 30, 4); // Volume of the container at that measure

            $table->unsignedBigInteger('container_id')->unsigned();
            $table->foreign('container_id')
                ->references('id')
                ->on('containers');

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

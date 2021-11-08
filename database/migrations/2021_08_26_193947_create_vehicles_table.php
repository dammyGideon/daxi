<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('balance');
            $table->unsignedBigInteger('rider_id');
            $table->foreign('rider_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('brand');
            $table->string('model');
            $table->string('plate_number');
            $table->string('color');
            $table->string('seatNo');
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
        Schema::dropIfExists('vehicles');
    }
}

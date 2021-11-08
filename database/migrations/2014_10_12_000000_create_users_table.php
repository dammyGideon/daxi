<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email', 200)->unique();
            $table->string('name')->nullable();
            $table->string('phone', 200)->unique();
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('otp')->nullable();
            $table->string('password');
            $table->string('vehicle_type')->nullable();
            $table->string('picture')->nullable();
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            $table->enum('role',['user','rider']);
            $table->string('active')->nullable();
            $table->string('registrationStatus')->nullable();

            $table->string('delete')->default('0');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
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
            $table->string('username');
            $table->string('email')->unique();
            $table->string('password'); 
            $table->string('full_name');
            $table->enum('division', ['Operation Manager', 'General Repair', 'Body Paint', 'Finance', 'HCD', 'Business Development', 'KSC - Palima', 'Finance - HCD', 'Sparepart', 'Training']);
            $table->enum('gender', ['pria', 'wanita']);
            $table->enum('company', ['AKASTRA', 'KSC']);
            $table->string('image');
            $table->enum('role', ['admin', 'member']);
            $table->timestamp('email_verified_at')->nullable();
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
};

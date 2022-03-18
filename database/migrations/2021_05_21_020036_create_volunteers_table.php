<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVolunteersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('volunteers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('userID')->constrained('users')->cascadeOnDelete();
            $table->string('firstName');
            $table->string('lastName');
            $table->integer('age');
            $table->string('volunteerTitle');
            $table->string('location')->nullable();
            $table->string('specialization');
            $table->string('section');
            $table->string('phone')->nullable();
            $table->string('college')->nullable();
            $table->string('description')->nullable();
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
        Schema::dropIfExists('volunteers');
    }
}

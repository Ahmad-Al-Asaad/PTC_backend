<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->boolean('groups')->nullable();
            $table->string('type',50);
            $table->string('location',50);
            $table->string('coachName',50);
            $table->bigInteger('cost')->nullable();
            $table->date('startDate');
            $table->date('endDate');
            $table->integer('currentNumber')->nullable();
            $table->integer('maxNumber')->nullable();
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
        Schema::dropIfExists('events');
    }
}

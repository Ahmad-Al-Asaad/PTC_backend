<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpportunitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opportunities', function (Blueprint $table) {
            $table->id();
            $table->string('title',50);
            $table->integer('state');   // 1 -> active / 2 -> inactive / 3 -> delete / 4 -> finished
            $table->integer('type');   // 1 -> placement / 2 -> Training
            $table->integer('companyID')->unsigned();
            $table->foreign('companyID')->references('id')->on('companies')->cascadeOnDelete();
            $table->integer('freeDesks');
            $table->date('lastDateForRegister');
            $table->bigInteger('salary')->nullable();
            $table->integer('time')->nullable(); // 1- partTime  2- fullTime
            $table->string('location',100)->nullable();
            $table->string('scope')->nullable();
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
        Schema::dropIfExists('opportunities');
    }
}

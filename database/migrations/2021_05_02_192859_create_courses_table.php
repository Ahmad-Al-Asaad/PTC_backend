<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class   CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title',50);
            $table->integer('state');  // 1 -> Active / 2 -> Inactive / 3 -> Deleted
            $table->foreignId('coachID')->constrained('trainers')->cascadeOnDelete();
            $table->string('location',70);
            $table->integer('Duration')->nullable();    // by Hours
            $table->time('startTime');
            $table->time('endTime');
            $table->date('startDate');
            $table->date('endDate');
            $table->integer('maxStudents')->nullable();
            $table->integer('CurrentStudents')->nullable();
            $table->bigInteger('cost')->nullable();
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
        Schema::dropIfExists('courses');
    }
}

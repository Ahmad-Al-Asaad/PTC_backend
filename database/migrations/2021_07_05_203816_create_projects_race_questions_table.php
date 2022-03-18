<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsRaceQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects_race_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('projectsRaceId')->constrained('projects_races')->cascadeOnDelete();
            $table->string('title');
            $table->integer('type'); //1- Free Question 2- Check Box 3- radio Box 4- File;
            $table->boolean('required');  //1- required  2- Non-required
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
        Schema::dropIfExists('projects_race_questions');
    }
}

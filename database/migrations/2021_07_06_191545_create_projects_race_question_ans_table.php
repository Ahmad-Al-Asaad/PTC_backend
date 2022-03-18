<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsRaceQuestionAnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects_race_question_ans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('questionID')->constrained('projects_race_questions')->cascadeOnDelete();
            $table->string('title');
            $table->integer('state');   //1-Active   2-InActive
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
        Schema::dropIfExists('projcets_race_question_ans');
    }
}

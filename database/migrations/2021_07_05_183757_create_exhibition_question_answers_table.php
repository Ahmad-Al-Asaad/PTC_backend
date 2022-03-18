<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExhibitionQuestionAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exhibition_question_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('questionID')->constrained('exhibition_questions')->cascadeOnDelete();
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
        Schema::dropIfExists('exhibition_question_answers');
    }
}

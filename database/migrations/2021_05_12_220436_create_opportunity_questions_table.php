<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpportunityQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opportunity_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('opportunityId')->constrained('opportunities')->cascadeOnDelete();
            $table->string('title');
            $table->integer('type'); // 1-Free OpportunityQuestion 2- Check Box 3- Radio Box 4- File
            $table->boolean('required'); // 1- required 2- Non-Required
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
        Schema::dropIfExists('opportunity_questions');
    }
}

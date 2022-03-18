<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExhibitionLoginRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exhibition_login_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('studentID')->constrained('students')->cascadeOnDelete();
            $table->foreignId('exhibitionsID')->constrained('exhibitions')->cascadeOnDelete();
            $table->integer('state');   // 1-stuck 2- Rejected 3-Accepted
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
        Schema::dropIfExists('exhibition_login_requests');
    }
}

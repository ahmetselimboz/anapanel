<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('surveyanswer', function (Blueprint $table) {
            $table->id();
            $table->string('survey_id');
            $table->string('answer1')->nullable();
            $table->string('answerhit1')->nullable();
            $table->text('answerimage1')->nullable();
            $table->string('answer2')->nullable();
            $table->string('answerhit2')->nullable();
            $table->text('answerimage2')->nullable();
            $table->string('answer3')->nullable();
            $table->string('answerhit3')->nullable();
            $table->text('answerimage3')->nullable();
            $table->string('answer4')->nullable();
            $table->string('answerhit4')->nullable();
            $table->text('answerimage4')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surveyanswer');
    }
};

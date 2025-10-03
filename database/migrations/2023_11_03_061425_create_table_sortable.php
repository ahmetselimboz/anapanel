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
        Schema::create('sortable', function (Blueprint $table) {
            $table->id();
            $table->string("type");
            $table->text("title")->nullable();
            $table->integer("category")->default(1);
            $table->integer("ads")->nullable();
            $table->integer("menu")->nullable();
            $table->integer("limit")->nullable();
            $table->text("file")->nullable();
            $table->string("design")->nullable();
            $table->string("color")->nullable();
            $table->integer("sortby")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sortable');
    }
};

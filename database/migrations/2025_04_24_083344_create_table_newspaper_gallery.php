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
        Schema::create('newspaper_gallery_images', function (Blueprint $table) {
            $table->id();
            $table->integer("gallery_id");
            $table->string("model_path")->nullable();
            $table->text("title")->nullable();
            $table->longText("images");
            $table->integer("sortby")->default(0);
            $table->timestamps();
        });

         Schema::table('enewspaper', function (Blueprint $table) {
            $table->date("date")->nullable();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('newspaper_gallery_images');
    }
};

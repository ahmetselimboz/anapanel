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
        Schema::create('photogalleryimages', function (Blueprint $table) {
            $table->id();
            $table->integer("photogallery_id");
            $table->text("title")->nullable();
            $table->longText("images");
            $table->integer("sortby")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photogalleryimages');
    }
};

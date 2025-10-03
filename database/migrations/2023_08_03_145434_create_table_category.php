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
        Schema::create('category', function (Blueprint $table) {
            $table->id();
            $table->tinyText('title');
            $table->tinyText('slug');
            $table->integer('parent_category')->default(0);
            $table->tinyText('category_type')->nullable(); // haber, galeri, video, firma, seri ilan için seçmeli
            $table->text('description')->nullable();
            $table->string('symbol')->nullable();
            $table->string('color')->nullable();
            $table->tinyText('keywords')->nullable();
            $table->integer('show_category_ads')->default(0);
            $table->integer('countnews')->nullable();
            $table->integer('publish')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category');
    }
};

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
        Schema::create('post', function (Blueprint $table) {
            $table->id();
            $table->longText('title');
            $table->longText('slug');
            $table->integer('category_id');
            $table->longText('keywords')->nullable();
            $table->longText('description')->nullable();
            $table->longText('detail');
            $table->longText('meta_title')->nullable();
            $table->longText('meta_description')->nullable();
            $table->integer('position')->default(0);
            $table->integer('sortby')->default(0);
            $table->text('images')->nullable();
            $table->json('extra')->nullable(); // haber kaynak, tıklandıkça eklenen alanlar, ilişkili haber ve foto, video embed, yorumlara kapalılık, ek resim alanları, FB resmi
            // $table->json('old_data')->nullable(); // aktarım olursa eski verileri buraya kaydet
            $table->tinyText('redirect_link')->nullable();
            $table->integer('show_title_slide')->default(0);
            $table->integer('hit')->default(0);
            $table->integer('publish')->default(0);
            // $table->boolean('is_archived')->default(false)->index();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post');
    }
};

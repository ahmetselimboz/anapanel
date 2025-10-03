<?php

use App\Models\Post;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('post_location')) {
            return;
        }
        Schema::create('post_location', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('post_id')->index();
            $table->tinyInteger('location_id')->index();
            $table->timestamps();
        });




        // Post::select('id','position')->get()->each(function($item){
        //     $item->postLocation()->sync($item->position);
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_location');
    }
};

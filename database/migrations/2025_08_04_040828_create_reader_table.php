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
        Schema::create('reader', function (Blueprint $table) {
            $table->id();
            $table->string("email")->nullable();
            $table->string("phone_number")->nullable();
            $table->timestamps();
            
            $table->foreignId('customer_id')
            ->constrained('customers') // customers tablosuna bağlanır
            ->onDelete('cascade');    // Customer silinirse reader'lar da silinir
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reader');
    }
};

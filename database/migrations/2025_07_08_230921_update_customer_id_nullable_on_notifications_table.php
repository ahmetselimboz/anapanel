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
            Schema::table('notification', function (Blueprint $table) {
        // Önce foreign key'i kaldır
        $table->dropForeign(['customer_id']);
        // Sonra nullable yap
        $table->unsignedBigInteger('customer_id')->nullable()->change();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
          Schema::table('notification', function (Blueprint $table) {
        $table->unsignedBigInteger('customer_id')->change(); // geri alınabilir
        $table->foreign('customer_id')->references('id')->on('customer')->onDelete('cascade');
    });
    }
};

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
        Schema::create('agency', function (Blueprint $table) {
            $table->id();
            $table->string('location')->nullable();
            $table->string('auto_publish')->default(0);
            $table->string('agency_one_user_code')->nullable();
            $table->string('agency_one_user_name')->nullable();
            $table->string('agency_one_user_pass')->nullable();
            $table->string('agency_one_city')->nullable();
            $table->string('agency_one_category')->nullable();
            $table->string('agency_two_user_code')->nullable();
            $table->string('agency_two_user_name')->nullable();
            $table->string('agency_two_user_pass')->nullable();
            $table->string('agency_two_city')->nullable();
            $table->string('agency_two_category')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agency');
    }
};

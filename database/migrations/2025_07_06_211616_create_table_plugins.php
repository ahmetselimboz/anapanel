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
        Schema::create('plugins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('version')->default('1.0.0');
            $table->string('author')->nullable();
            $table->string('author_url')->nullable();
            $table->text('plugin_url')->nullable();
            $table->text('documentation_url')->nullable();
            $table->boolean('is_active')->default(false);
            $table->boolean('is_installed')->default(false);
            $table->json('settings')->nullable();
            $table->json('dependencies')->nullable();
            $table->text('requirements')->nullable();
            $table->string('minimum_php_version')->nullable();
            $table->string('minimum_laravel_version')->nullable();
            $table->text('changelog')->nullable();
            $table->string('license')->nullable();
            $table->text('license_url')->nullable();
            $table->string('icon')->nullable();
            $table->text('screenshots')->nullable();
            $table->integer('download_count')->default(0);
            $table->decimal('rating', 3, 2)->default(0.00);
            $table->integer('rating_count')->default(0);
            $table->timestamp('last_updated')->nullable();
            $table->timestamp('installed_at')->nullable();
            $table->timestamp('activated_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plugins');
    }
};

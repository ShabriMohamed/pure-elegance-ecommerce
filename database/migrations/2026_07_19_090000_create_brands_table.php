<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('brands')) {
            return;
        }

        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            // Matches products.brand (a plain string) so existing catalogue data keeps
            // working; the row simply enriches a brand name with presentation assets.
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('logo_path')->nullable();
            $table->string('background_path')->nullable();
            $table->string('tagline')->nullable();
            // Fallback accent used for the gradient when no background is uploaded.
            $table->string('accent_color', 9)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};

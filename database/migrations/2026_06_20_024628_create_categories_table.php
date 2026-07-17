<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            // Self-referential hierarchy. nullOnDelete (not cascade) so deleting a
            // parent never silently wipes descendants.
            $table->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->string('image')->nullable();
            $table->enum('gender', ['men', 'women', 'unisex'])->default('unisex');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->timestamps();
            // No soft deletes: the Category model does not use the SoftDeletes trait.

            $table->index(['parent_id', 'is_active']);
            $table->index(['gender', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};

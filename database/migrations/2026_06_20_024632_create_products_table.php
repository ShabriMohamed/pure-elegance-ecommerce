<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->nullable()->unique();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->decimal('price', 12, 2);
            $table->decimal('sale_price', 12, 2)->nullable();
            $table->decimal('cost_price', 12, 2)->nullable();
            // restrictOnDelete mirrors Admin\CategoryController@destroy, which blocks
            // deleting a category that still has products.
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->enum('gender', ['men', 'women', 'unisex'])->default('unisex');
            $table->string('brand')->nullable();
            $table->integer('stock_quantity')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_new_arrival')->default(false);
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->unsignedBigInteger('view_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['category_id', 'is_active']);
            $table->index(['gender', 'is_active']);
            $table->index(['is_featured', 'is_active']);
            $table->index(['is_new_arrival', 'is_active']);
            $table->index('brand');
            $table->index('sale_price');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

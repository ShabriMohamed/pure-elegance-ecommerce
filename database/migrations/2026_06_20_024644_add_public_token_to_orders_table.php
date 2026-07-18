<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('orders', 'public_token')) {
            return;
        }

        Schema::table('orders', function (Blueprint $table) {
            // Unguessable, shareable token for the public order/tracking link
            // (used in the WhatsApp order card and by customers to track status).
            $table->string('public_token', 64)->nullable()->unique()->after('order_number');
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('orders', 'public_token')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('public_token');
            });
        }
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Events indexes
        Schema::table('events', function (Blueprint $table) {
            $table->index('status');
            $table->index('user_id');
            $table->index('category_id');
            $table->index('published_at');
            $table->index(['status', 'published_at']);
        });

        // Orders indexes
        Schema::table('orders', function (Blueprint $table) {
            $table->index('payment_status');
            $table->index('event_id');
            $table->index('payment_reference');
            $table->index(['event_id', 'payment_status']);
        });

        // Order items indexes
        Schema::table('order_items', function (Blueprint $table) {
            $table->index('order_id');
            $table->index('ticket_id');
            $table->index('ticket_code');
            $table->index('is_checked_in');
        });

        // Tickets indexes
        Schema::table('tickets', function (Blueprint $table) {
            $table->index('event_id');
            $table->index('is_active');
        });

        // Notifications indexes
        Schema::table('notifications', function (Blueprint $table) {
            $table->index('user_id');
            $table->index(['user_id', 'is_read']);
        });

        // Promo codes indexes
        Schema::table('promo_codes', function (Blueprint $table) {
            $table->index('event_id');
            $table->index('code');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['category_id']);
            $table->dropIndex(['published_at']);
            $table->dropIndex(['status', 'published_at']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['payment_status']);
            $table->dropIndex(['event_id']);
            $table->dropIndex(['payment_reference']);
            $table->dropIndex(['event_id', 'payment_status']);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropIndex(['order_id']);
            $table->dropIndex(['ticket_id']);
            $table->dropIndex(['ticket_code']);
            $table->dropIndex(['is_checked_in']);
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->dropIndex(['event_id']);
            $table->dropIndex(['is_active']);
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['user_id', 'is_read']);
        });

        Schema::table('promo_codes', function (Blueprint $table) {
            $table->dropIndex(['event_id']);
            $table->dropIndex(['code']);
            $table->dropIndex(['is_active']);
        });
    }
};

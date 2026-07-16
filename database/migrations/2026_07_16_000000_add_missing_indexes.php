<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('waitlists', function (Blueprint $table) {
            $table->index('ticket_id');
            $table->index('event_id');
        });

        Schema::table('bank_accounts', function (Blueprint $table) {
            $table->index('user_id');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->index('user_id');
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->index('event_id');
        });
    }

    public function down(): void
    {
        Schema::table('waitlists', function (Blueprint $table) {
            $table->dropIndex(['ticket_id']);
            $table->dropIndex(['event_id']);
        });

        Schema::table('bank_accounts', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex(['event_id']);
        });
    }
};

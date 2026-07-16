<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->foreignId('event_id')->nullable()->after('user_id')
                ->constrained()->nullOnDelete();
            $table->index(['user_id', 'event_id'], 'notifications_user_event_index');
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex('notifications_user_event_index');
            $table->dropForeign(['event_id']);
            $table->dropColumn('event_id');
        });
    }
};

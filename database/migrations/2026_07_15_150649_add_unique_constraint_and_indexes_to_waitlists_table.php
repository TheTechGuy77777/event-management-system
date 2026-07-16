<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('waitlists', function (Blueprint $table) {
            $table->unique(['ticket_id', 'email'], 'waitlists_ticket_email_unique');
            $table->index('is_notified', 'waitlists_is_notified_index');
            $table->index('priority_expires_at', 'waitlists_priority_expires_at_index');
        });
    }

    public function down(): void
    {
        Schema::table('waitlists', function (Blueprint $table) {
            $table->dropUnique('waitlists_ticket_email_unique');
            $table->dropIndex('waitlists_is_notified_index');
            $table->dropIndex('waitlists_priority_expires_at_index');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->enum('event_mode', ['physical', 'online', 'hybrid'])
                ->default('physical')
                ->after('event_type');
            $table->enum('platform', [
                'zoom',
                'zoom_webinar',
                'google_meet',
                'microsoft_teams',
                'youtube_live',
                'custom',
            ])->nullable()->after('event_mode');
            $table->string('meeting_link')->nullable()->after('platform');
            $table->string('meeting_id')->nullable()->after('meeting_link');
            $table->string('meeting_passcode')->nullable()->after('meeting_id');
            $table->string('whatsapp_link')->nullable()->after('meeting_passcode');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn([
                'event_mode',
                'platform',
                'meeting_link',
                'meeting_id',
                'meeting_passcode',
                'whatsapp_link',
            ]);
        });
    }
};

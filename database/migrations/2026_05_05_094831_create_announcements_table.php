<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sent_by')->constrained('users')->cascadeOnDelete();
            $table->string('subject');
            $table->text('message');
            $table->string('recipient_type'); // 'all' or 'specific'
            $table->string('recipient_name')->nullable(); // name of specific manager
            $table->integer('recipients_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};

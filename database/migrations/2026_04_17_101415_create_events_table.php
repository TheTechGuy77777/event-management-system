<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name', 75);
            $table->string('slug')->unique();
            $table->longText('description')->nullable();
            $table->string('event_type')->nullable();
            $table->string('country')->nullable();
            $table->string('location')->nullable();
            $table->boolean('is_virtual')->default(false);
            $table->string('virtual_link')->nullable();
            $table->datetime('start_date')->nullable();
            $table->datetime('end_date')->nullable();
            $table->string('timezone')->default('Africa/Lagos');
            $table->boolean('is_recurring')->default(false);
            $table->string('recurrence_rule')->nullable();
            $table->date('recurrence_end')->nullable();
            $table->string('cover_image')->nullable();
            $table->enum('payment_model', ['attendee_pays', 'manager_pays'])->default('attendee_pays');
            $table->decimal('commission_rate', 5, 2)->default(0);
            $table->enum('status', ['draft', 'published', 'ended', 'cancelled'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->string('instagram')->nullable();
            $table->string('twitter')->nullable();
            $table->string('facebook')->nullable();
            $table->string('website')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};

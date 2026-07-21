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
        Schema::create('otps', function (Blueprint $table) {
            $table->id();

            // User this OTP belongs to
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // Purpose of this OTP
            $table->string('purpose'); // email_verification, password_reset

            // Hashed OTP code
            $table->string('code');

            // Number of failed verification attempts
            $table->unsignedTinyInteger('attempts')->default(0);

            // Prevent resend spam
            $table->timestamp('last_sent_at')->nullable();

            // OTP expiration
            $table->timestamp('expires_at');

            // Prevent OTP reuse
            $table->timestamp('verified_at')->nullable();

            $table->timestamps();

            // Helpful indexes
            $table->index(['user_id', 'purpose']);
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otps');
    }
};

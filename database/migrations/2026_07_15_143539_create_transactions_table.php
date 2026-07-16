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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('gateway');
            $table->string('reference')->unique();

            $table->string('status');

            $table->decimal('amount', 12, 2);

            $table->decimal('gateway_fee', 12, 2)->nullable();

            $table->decimal('platform_commission', 12, 2)->nullable();

            $table->decimal('manager_earnings', 12, 2)->nullable();

            $table->json('payload')->nullable();

            $table->json('metadata')->nullable();

            $table->timestamp('completed_at')->nullable();

            $table->timestamp('refunded_at')->nullable();

            $table->timestamps();

            $table->index(['order_id', 'gateway']);
            $table->index(['reference', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};

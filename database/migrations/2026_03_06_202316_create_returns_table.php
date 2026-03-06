<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->text('reason');
            $table->enum('status', [
                'requested',
                'approved',
                'rejected',
                'completed'
            ])->default('requested');
            $table->decimal('refund_amount', 10, 2)->default(0);
            $table->enum('refund_method', [
                'original',
                'store_credit',
                'transfer'
            ])->default('original');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('returns');
    }
};
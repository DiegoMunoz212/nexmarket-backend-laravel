<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seller_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');
            $table->date('date');
            $table->integer('total_sales')->default(0);
            $table->decimal('total_revenue', 10, 2)->default(0);
            $table->integer('total_orders')->default(0);
            $table->integer('total_views')->default(0);
            $table->decimal('conversion_rate', 5, 2)->default(0);
            $table->decimal('avg_rating', 3, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seller_analytics');
    }
};
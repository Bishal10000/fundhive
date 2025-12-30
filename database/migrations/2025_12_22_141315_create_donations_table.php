<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('transaction_id')->unique();
            $table->decimal('amount', 15, 2);
            $table->decimal('platform_fee', 10, 2)->default(0);
            $table->string('currency')->default('USD');
            $table->string('payment_method')
                  ->check("payment_method IN ('esewa', 'khalti', 'card', 'bank')");
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded']);
            $table->string('donor_name');
            $table->string('donor_email');
            $table->boolean('is_anonymous')->default(false);
            $table->text('message')->nullable();
            $table->json('payment_details')->nullable();
            $table->decimal('fraud_score', 5, 2)->default(0);  // default 0
            $table->boolean('is_flagged')->default(false);     // default false
            $table->timestamps();
        });
    }
};
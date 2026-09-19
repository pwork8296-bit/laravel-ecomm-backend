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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->index();
            $table->string('razorpay_order_id', 100)->nullable()->index();
            $table->string('razorpay_payment_id', 100)->nullable()->index();
            $table->decimal('amount', 10, 2)->default(0.00);
            $table->string('currency', 10)->default('INR');
            $table->string('status', 50)->default('pending');
            $table->boolean('signature_verified')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('cart_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\Sheva\Cart\Models\Cart::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->unsignedBigInteger('payment_id')
                ->unique()
                ->nullable()
                ->from(100000);

            $table->string('method');

            $table->string('status')->nullable();
            $table->string('currency', 10);
            $table->unsignedBigInteger('amount');

            $table->timestamps();
        });
    }

    public function down()
    {
        if (!app()->isLocal()) {
            return;
        }
        Schema::dropIfExists('cart_payments');
    }
};

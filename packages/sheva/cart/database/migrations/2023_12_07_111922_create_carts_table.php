<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')
                ->unique()
                ->nullable()
                ->from(10000);

            $table->uuid('cart_user_uuid')->index();

            $table->unsignedBigInteger('total_price')->nullable();
            $table->unsignedBigInteger('products_total_price')->nullable();
            $table->unsignedBigInteger('products_discount_total_price')->nullable();
            $table->unsignedBigInteger('delivery_price')->nullable();

            $table->string('comment')->nullable();

            $table->timestamp('cart_at')->nullable();
            $table->timestamp('cart_finish_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        if (!app()->isLocal()) {
            return;
        }
        Schema::dropIfExists('carts');
    }
};

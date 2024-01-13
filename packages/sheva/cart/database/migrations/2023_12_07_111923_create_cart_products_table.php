<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('cart_products', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(\Sheva\Cart\Models\Cart::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->string('slug', 150)->index();
            $table->string('sku', 100);
            $table->string('product_type', 30);
            $table->unsignedInteger('quantity');
            $table->unsignedInteger('available_quantity');

            $table->jsonb('name');
            $table->string('image', 255)->nullable();

            $table->unsignedBigInteger('buy_price');
            $table->unsignedBigInteger('buy_delivery_price')->default(0);
            $table->string('buy_url', 255)->nullable();

            $table->unsignedBigInteger('site_price');
            $table->unsignedBigInteger('site_delivery_price')->default(0);
            $table->unsignedBigInteger('site_discount')->default(0);

            $table->timestamps();

            $table->unique(['cart_id', 'slug',]);
        });
    }

    public function down()
    {
        if (!app()->isLocal()) {
            return;
        }
        Schema::dropIfExists('cart_products');
    }
};

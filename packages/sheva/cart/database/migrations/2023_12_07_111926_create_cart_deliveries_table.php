<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('cart_deliveries', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('cart_id')->index();

            $table->string('carrier');
            $table->string('type');

            $table->unsignedBigInteger('delivery_price')->default(0);
            $table->timestamp('delivery_at')->nullable();

            $table->string('country_code', 10)->nullable();
            $table->string('city_name', 70)->nullable();
            $table->string('city_code', 10)->nullable();
            $table->string('street', 70)->nullable();
            $table->string('address_code', 10)->nullable();
            $table->string('house', 5)->nullable();
            $table->string('flat', 5)->nullable();
            $table->string('floor', 5)->nullable();
            $table->string('has_elevator')->default(false);

            $table->string('company', 70)->nullable();
            $table->string('vat', 70)->nullable();

            $table->foreign('cart_id')->references('id')
                ->on('carts')->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down()
    {
        if (!app()->isLocal()) {
            return;
        }
        Schema::dropIfExists('cart_deliveries');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('cart_users', function (Blueprint $table) {
            $table->uuid()->unique();

            $table->foreignIdFor(config('cart.user_model'), 'user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->string('name', 70);
            $table->string('last_name', 70)->nullable();
            $table->string('surname', 70)->nullable();
            $table->string('phone_number', 20);
            $table->string('email', 70)->nullable();

            $table->boolean('another_recipient')->default(false);
            $table->string('recipient_name', 70)->nullable();
            $table->string('recipient_last_name', 70)->nullable();
            $table->string('recipient_surname', 70)->nullable();
            $table->string('recipient_phone_number', 20);

            $table->foreign('uuid')->references('cart_user_uuid')
                ->on('carts')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down()
    {
        if (!app()->isLocal()) {
            return;
        }
        Schema::dropIfExists('cart_users');
    }
};

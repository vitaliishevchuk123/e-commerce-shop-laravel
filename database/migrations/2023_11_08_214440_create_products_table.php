<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up()
	{
		Schema::create('products', function (Blueprint $table) {
			$table->id();

            $table->foreignIdFor(\App\Models\Brand::class)
                ->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('sku', 100)->unique();
            $table->jsonb('name');
            $table->string('slug', 100)->unique()->nullable();
            $table->json('description')->nullable();
            $table->unsignedInteger('quantity')->default(0);
            $table->decimal('price', 12);
            $table->decimal('sale_price', 12);
            $table->boolean('active')->default(1);
            $table->boolean('featured')->default(0);

            $table->softDeletes();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::dropIfExists('products');
	}
};

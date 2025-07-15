<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('mysql-shop')->create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('product_category_id');
            $table->foreign('product_category_id')->references('id')->on('product_categories');
            $table->string('name');
            $table->longText('description');
            $table->float('price')->unsigned();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mysql-shop')->dropIfExists('products');
    }
};

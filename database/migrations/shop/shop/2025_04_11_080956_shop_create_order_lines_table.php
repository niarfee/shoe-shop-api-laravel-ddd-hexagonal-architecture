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
        Schema::connection('mysql-shop')->create('order_lines', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('order_id');
            $table->foreign('order_id')->references('id')->on('orders');
            $table->uuid('product_variant_id');
            $table->foreign('product_variant_id')->references('id')->on('product_variants');
            $table->integer('units');
            $table->float('unit_price')->unsigned();
            $table->timestamps();
            $table->softDeletes('deleted_at');
            $table->unique(['order_id', 'product_variant_id', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mysql-shop')->dropIfExists('order_lines');
    }
};

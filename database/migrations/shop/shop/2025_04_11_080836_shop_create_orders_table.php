<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Src\Shop\Order\Domain\ValueObject\OrderStatus;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('mysql-shop')->create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->unsignedInteger('status')->default(OrderStatus::IN_CART);
            $table->float('total_price')->unsigned()->default(0.00);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mysql-shop')->dropIfExists('orders');
    }
};

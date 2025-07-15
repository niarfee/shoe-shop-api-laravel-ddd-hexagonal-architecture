<?php

declare(strict_types=1);

namespace Tests\Utils\Scenarios;

use Src\Shop\Order\Domain\Order;
use Src\Shop\Order\Domain\OrderLine;
use Src\Shop\Order\Domain\ValueObject\OrderId;
use Src\Shop\Order\Domain\ValueObject\OrderStatus;
use Src\Shop\Order\Domain\ValueObject\OrderTotalPrice;
use Src\Shop\Order\Infrastructure\Persistence\Eloquent\OrderEloquentModel;
use Src\Shop\Shared\Domain\ValueObject\CustomerId;
use Tests\Src\Shop\Order\Domain\OrderMother;
use Tests\Src\Shop\Shared\Domain\ValueObject\ProductVariantStockMother;
use Tests\Src\Shop\Shared\Domain\ValueObject\ProductVariantStockRequestedMother;
use Tests\Src\Shop\Shared\Domain\ValueObject\StockRequestMother;

final class OrderScenarioBuilder extends ScenarioBuilder
{
    public static function orderMotherMakeAndPersist(
        ?OrderId $id = null,
        ?CustomerId $customerId = null,
        ?OrderStatus $status = null,
        ?OrderTotalPrice $totalPrice = null,
    ): Order {
        $order = OrderMother::make(
            id: $id,
            customerId: $customerId,
            status: $status,
            totalPrice: $totalPrice,
        );

        OrderEloquentModel::factory()->create([
            'id' => $order->id()->value(),
            'customer_id' => $order->customerId()->value(),
            'status' => $order->status()->value(),
            'total_price' => $order->totalPrice()->value(),
        ]);

        return $order;
    }

    public static function orderMotherMakeAndPersistWithDependenciesWithNLines(
        CustomerId $customerId,
        OrderStatus $status,
        int $numberOfLines = 1,
    ): Order {
        $order = self::orderMotherMakeAndPersist(
            customerId: $customerId,
            status: $status,
        );

        for ($i = 0; $i < $numberOfLines; $i++) {
            $order->addLine(
                self::orderLineMotherMakeAndPersistWithDependenciesFromOrder($order->id()),
            );
        }

        return $order;
    }

    private static function orderLineMotherMakeAndPersistWithDependenciesFromOrder(OrderId $orderId): OrderLine
    {
        $productVariantStock = ProductVariantStockMother::make();
        $productVariantStockRequested = ProductVariantStockRequestedMother::make();

        $orderLine = OrderLineScenarioBuilder::orderLineMotherMakeAndPersist(
            productVariantId: ProductVariantScenarioBuilder::productVariantMotherMakeAndPersist(
                productId: ProductScenarioBuilder::productMotherMakeAndPersist(
                    productCategoryId: ProductCategoryScenarioBuilder::productCategoryMotherMakeAndPersist()->id(),
                )->id(),
                stock: $productVariantStock,
            )->id(),
            stockRequest: StockRequestMother::make($productVariantStockRequested, $productVariantStock),
            orderId: $orderId,
        );

        return $orderLine;
    }
}

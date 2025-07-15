<?php

declare(strict_types=1);

namespace Database\Seeders\Helpers;

use Src\Shop\Order\Domain\Order;
use Src\Shop\Order\Domain\OrderLine;
use Src\Shop\Order\Domain\ValueObject\OrderTotalPrice;
use Src\Shop\Order\Infrastructure\Persistence\Eloquent\OrderEloquentModel;
use Src\Shop\Order\Infrastructure\Persistence\Eloquent\OrderLineEloquentModel;
use Src\Shop\Product\Domain\ProductVariant;
use Tests\Src\Shop\Order\Domain\OrderLineMother;
use Tests\Src\Shop\Order\Domain\OrderMother;
use Tests\Src\Shop\Order\Domain\ValueObject\OrderIdMother;
use Tests\Src\Shop\Order\Domain\ValueObject\OrderLineIdMother;
use Tests\Src\Shop\Order\Domain\ValueObject\OrderLineUnitPriceMother;
use Tests\Src\Shop\Order\Domain\ValueObject\OrderTotalPriceMother;
use Tests\Src\Shop\Shared\Domain\ValueObject\CustomerIdMother;
use Tests\Src\Shop\Shared\Domain\ValueObject\ProductVariantIdMother;
use Tests\Src\Shop\Shared\Domain\ValueObject\ProductVariantStockMother;
use Tests\Src\Shop\Shared\Domain\ValueObject\ProductVariantStockRequestedMother;
use Tests\Src\Shop\Shared\Domain\ValueObject\StockRequestMother;

class OrderSeederHelper extends SeederHelper
{
    private static function getFreeProductVariantForOrder(Order $order, array $products): ?ProductVariant
    {
        shuffle($products);

        foreach ($products as $product) {
            $variants = $product->variants();
            shuffle($variants);
            foreach ($variants as $variant) {
                if (!$order->hasLine($variant->id())) {
                    return $variant;
                }
            }
        }

        return null;
    }

    public function buildFake(
        array $customers,
        array $products,
        int $quantityOrders,
        array $quantityRandomOrderLines = [1, 2, 3],
    ): array {
        $ordersWithLines = [];

        foreach (range(1, $quantityOrders) as $_) {
            $orderId = OrderIdMother::make();

            $customerId = CustomerIdMother::make(
                fake()->randomElement($customers)->id()->value(),
            );

            $order = OrderMother::make(
                id: $orderId,
                customerId: $customerId,
                totalPrice: OrderTotalPriceMother::make(
                    OrderTotalPrice::zero()->value(),
                ),
            );

            $quantityOrderLines = fake()->randomElement($quantityRandomOrderLines);

            foreach (range(1, $quantityOrderLines) as $_) {
                $product = fake()->randomElement($products);
                $variant = self::getFreeProductVariantForOrder($order, $products);
                if ($variant === null) {
                    break;
                }

                $productVariantId = ProductVariantIdMother::make($variant->id()->value());
                $units = StockRequestMother::make(
                    ProductVariantStockRequestedMother::make(),
                    ProductVariantStockMother::make($variant->stock()->value()),
                )->toOrderLineUnits();
                $unitPrice = OrderLineUnitPriceMother::make($product->price()->value());

                $line = OrderLineMother::make(
                    id: OrderLineIdMother::make(),
                    orderId: $orderId,
                    productVariantId: $productVariantId,
                    units: $units,
                    unitPrice: $unitPrice,
                );

                $order->addLine($line);
            }

            $ordersWithLines[] = $order;
        }

        return $ordersWithLines;
    }

    public function fromDomainListToArrayList(array $ordersWithLines): array
    {
        $ordersWithLinesArray = [];

        foreach ($ordersWithLines as $order) {
            $ordersWithLinesArray[] = [
                'id' => $order->id()->value(),
                'customer_id' => $order->customerId()->value(),
                'status' => $order->status()->value(),
                'total_price' => $order->totalPrice()->value(),
                'lines' => array_map(
                    fn (OrderLine $line) => [
                        'id' => $line->id()->value(),
                        'product_variant_id' => $line->productVariantId()->value(),
                        'units' => $line->units()->value(),
                        'unit_price' => $line->unitPrice()->value(),
                    ],
                    $order->lines(),
                ),
            ];
        }

        return $ordersWithLinesArray;
    }

    public function persistList(array $ordersWithLines): void
    {
        parent::persistListWithSubModelBase(
            modelClass: OrderEloquentModel::class,
            subModelClass: OrderLineEloquentModel::class,
            items: $ordersWithLines,
            keySubModel: 'lines',
            keyFk: 'order_id',
        );
    }
}

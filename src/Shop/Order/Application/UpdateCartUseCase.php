<?php

declare(strict_types=1);

namespace Src\Shop\Order\Application;

use Illuminate\Support\Facades\Auth;
use Src\Shared\Domain\TransactionalInterface;
use Src\Shop\Order\Application\Response\Dto\OrderResponseDTO;
use Src\Shop\Order\Application\Response\Mapper\OrderResponseMapper;
use Src\Shop\Order\Application\Service\GetOrCreateOrderCartService;
use Src\Shop\Order\Domain\Order;
use Src\Shop\Order\Domain\OrderLine;
use Src\Shop\Order\Domain\OrderLineRepositoryInterface;
use Src\Shop\Order\Domain\OrderRepositoryInterface;
use Src\Shop\Order\Domain\ValueObject\OrderLineId;
use Src\Shop\Order\Domain\ValueObject\OrderLineUnitPrice;
use Src\Shop\Product\Domain\ProductRepositoryInterface;
use Src\Shop\Shared\Domain\ValueObject\CustomerId;
use Src\Shop\Shared\Domain\ValueObject\ProductVariantId;
use Src\Shop\Shared\Domain\ValueObject\ProductVariantStockRequested;
use Src\Shop\Shared\Domain\ValueObject\StockRequest;

final class UpdateCartUseCase
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
        private OrderLineRepositoryInterface $orderLineRepository,
        private ProductRepositoryInterface $productRepository,
        private GetOrCreateOrderCartService $getOrCreateOrderCartService,
        private OrderResponseMapper $orderResponseMapper,
        private TransactionalInterface $transactional,
    ) {
    }

    /**
     * @throws ProductVariantNotFoundException If variant does not exist
     */
    public function __invoke(
        string $productVariantId,
        int $units,
        CustomerId $customerId,
    ): OrderResponseDTO {
        $productVariantId = new ProductVariantId($productVariantId);
        $stockRequested = new ProductVariantStockRequested($units);

        return $this->transactional->run(function () use ($productVariantId, $stockRequested, $customerId) {
            // $customerIdzzzz = new CustomerId(Auth::user()->customer_id);
            $orderCart = $this->getOrCreateOrderCartService->__invoke($customerId);

            $product = $this->productRepository->findByProductVariantId($productVariantId);
            $productVariant = $product->variantById($productVariantId);

            $stockRequest = StockRequest::create($stockRequested, $productVariant->stock());
            $orderLineUnitPrice = new OrderLineUnitPrice($product->price()->value());

            $orderLineId = $this->getOrCreateOrderLine(
                $orderCart,
                $productVariantId,
                $stockRequest,
                $orderLineUnitPrice,
            );

            if (!$orderLineId) {
                return $this->orderResponseMapper->map($orderCart);
            }

            $orderCart = $this->applyAndPersist($orderCart, $orderLineId, $stockRequest, $orderLineUnitPrice);

            return $this->orderResponseMapper->map($orderCart);
        });
    }

    private function getOrCreateOrderLine(
        Order $orderCart,
        ProductVariantId $productVariantId,
        StockRequest $stockRequest,
        OrderLineUnitPrice $orderLineUnitPrice,
    ): ?OrderLineId {
        $existingLine = $orderCart->lineByProductVariantId($productVariantId);
        if ($existingLine) {
            return $existingLine->id();
        }

        $newLine = OrderLine::createBasedOnStock(
            id: OrderLineId::generate(),
            orderId: $orderCart->id(),
            productVariantId: $productVariantId,
            stockRequest: $stockRequest,
            unitPrice: $orderLineUnitPrice,
        );

        // If we need to create a new line with 0 units, we do not create it.
        if ($newLine->units()->isEmpty()) {
            return null;
        }

        $orderCart->addLine($newLine);
        $this->orderLineRepository->save($newLine);
        return $newLine->id();
    }

    private function applyAndPersist(
        Order $orderCart,
        OrderLineId $orderLineId,
        StockRequest $stockRequest,
        OrderLineUnitPrice $orderLineUnitPrice,
    ): Order {
        $orderLineUpdated = $orderCart->updateLineUnitsBasedOnStock($orderLineId, $stockRequest);

        if ($orderLineUpdated === null) {
            $this->orderLineRepository->delete($orderLineId);
        } else {
            $orderLineUpdated = $orderCart->updateLineUnitPrice($orderLineId, $orderLineUnitPrice);
            $this->orderLineRepository->save($orderLineUpdated);
        }

        $this->orderRepository->save($orderCart);

        return $orderCart;
    }
}

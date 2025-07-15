<?php

declare(strict_types=1);

namespace Src\Shop\Order\Application;

use Illuminate\Support\Facades\Auth;
use Src\Shared\Domain\TransactionalInterface;
use Src\Shop\Order\Application\Response\Dto\OrderResponseDTO;
use Src\Shop\Order\Application\Response\Mapper\OrderResponseMapper;
use Src\Shop\Order\Domain\Exception\CartEmptyException;
use Src\Shop\Order\Domain\Exception\CartNotFoundException;
use Src\Shop\Order\Domain\OrderRepositoryInterface;
use Src\Shop\Product\Domain\ProductRepositoryInterface;
use Src\Shop\Product\Domain\ProductVariantRepositoryInterface;
use Src\Shop\Shared\Domain\ValueObject\CustomerId;

final class ConfirmCartUseCase
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
        private ProductRepositoryInterface $productRepository,
        private ProductVariantRepositoryInterface $productVariantRepository,
        private OrderResponseMapper $orderResponseMapper,
        private TransactionalInterface $transactional,
    ) {
    }

    /**
     * @throws CartNotFoundException If cart does not exist
     * @throws CartEmptyException If cart is empty
     */
    public function __invoke(): OrderResponseDTO
    {
        $customerId = new CustomerId(Auth::user()->customer_id);
        $orderCart = $this->orderRepository->searchCartByCustomerId($customerId);

        if (!$orderCart) {
            throw new CartNotFoundException($customerId);
        }
        if ($orderCart->isEmpty()) {
            throw new CartEmptyException($orderCart->id());
        }

        return $this->transactional->run(function () use ($orderCart) {
            foreach ($orderCart->lines() as $orderLine) {
                $product = $this->productRepository->findByProductVariantId($orderLine->productVariantId());
                $productVariant = $product->variantById($orderLine->productVariantId());

                $productVariant = $product->updateVariantDecrementStock($productVariant->id(), $orderLine->units());
                $this->productVariantRepository->save($productVariant);
            }

            $orderCart->changeStatusToPendingShipment();
            $this->orderRepository->save($orderCart);

            return $this->orderResponseMapper->map($orderCart);
        });
    }
}

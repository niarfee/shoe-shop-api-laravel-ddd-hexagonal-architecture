<?php

declare(strict_types=1);

namespace Tests\Src\Shop\Order\Application;

use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\MockObject\MockObject;
use Src\Shop\Order\Application\Response\Dto\OrderResponseDTO;
use Src\Shop\Order\Application\Response\Mapper\OrderResponseMapper;
use Src\Shop\Order\Application\Service\GetOrCreateOrderCartService;
use Src\Shop\Order\Application\UpdateCartUseCase;
use Src\Shop\Order\Domain\OrderLineRepositoryInterface;
use Src\Shop\Order\Domain\OrderRepositoryInterface;
use Src\Shop\Product\Domain\ProductRepositoryInterface;
use Tests\Src\Shared\Infrastructure\Persistence\Eloquent\InMemoryTransactional;
use Tests\Src\Shop\Order\Domain\OrderMother;
use Tests\Src\Shop\Product\Domain\ProductMother;
use Tests\Src\Shop\Product\Domain\ProductVariantMother;
use Tests\Src\Shop\Shared\Domain\ValueObject\CustomerIdMother;
use Tests\Utils\UnitPhpUnitTestCase;

class UpdateCartUseCaseTest extends UnitPhpUnitTestCase
{
    private UpdateCartUseCase $updateCartUseCase;
    private OrderRepositoryInterface|MockObject $orderRepository;
    private OrderLineRepositoryInterface|MockObject $orderLineRepository;
    private ProductRepositoryInterface|MockObject $productRepository;
    private GetOrCreateOrderCartService|MockObject $getOrCreateOrderCartService;
    private OrderResponseMapper|MockObject $orderResponseMapper;
    private mixed $transactional;

    #[Group('unit')]
    public function test_invoke_returns_mapped_response_when_order_line_has_zero_units(): void
    {
        // GIVEN
        $product = ProductMother::make();
        $productVariant = ProductVariantMother::make(
            productId: $product->id(),
        );
        $product->addVariant($productVariant);
        $productVariantId = $productVariant->id();
        $customerId = CustomerIdMother::make();
        $orderCart = OrderMother::make(customerId: $customerId);

        // Configurar el repositorio para devolver el carrito
        $this->orderRepository->method('searchCartByCustomerId')
            ->with($customerId)
            ->willReturn($orderCart);

        // Configurar el repositorio de productos
        $this->productRepository->method('findByProductVariantId')
            ->with($productVariantId)
            ->willReturn($product);

        // WHEN
        $result = $this->updateCartUseCase->__invoke(
            $productVariantId->value(),
            0,
            $customerId,
        );

        // THEN
        // Verificar que el resultado es una instancia de OrderResponseDTO
        $this->assertInstanceOf(OrderResponseDTO::class, $result);

        // Verificar que el ID del cliente en la respuesta coincide
        $this->assertEquals($customerId->value(), $result->customerId);

        // Verificar que el carrito no tiene líneas (ya que las unidades son 0)
        $this->assertEmpty($result->lines);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->orderRepository = $this->createMock(OrderRepositoryInterface::class);
        $this->orderLineRepository = $this->createMock(OrderLineRepositoryInterface::class);
        $this->productRepository = $this->createMock(ProductRepositoryInterface::class);
        $this->orderResponseMapper = new OrderResponseMapper();
        $this->transactional = new InMemoryTransactional();

        $this->getOrCreateOrderCartService = new GetOrCreateOrderCartService(
            $this->orderRepository,
        );

        $this->updateCartUseCase = new UpdateCartUseCase(
            $this->orderRepository,
            $this->orderLineRepository,
            $this->productRepository,
            $this->getOrCreateOrderCartService,
            $this->orderResponseMapper,
            $this->transactional,
        );
    }
}

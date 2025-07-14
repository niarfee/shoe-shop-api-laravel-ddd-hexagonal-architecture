<?php

declare(strict_types=1);

namespace Tests\Src\Shop\User\Application;

use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\MockObject\MockObject;
use Src\Shop\Customer\Domain\CustomerRepositoryInterface;
use Src\Shop\Customer\Domain\Exception\CustomerNotFoundException;
use Src\Shop\Shared\Domain\ValueObject\CustomerId;
use Src\Shop\User\Application\CustomerValidator;
use Tests\Utils\UnitPhpUnitTestCase;

final class CustomerValidatorTest extends UnitPhpUnitTestCase
{
    private CustomerRepositoryInterface|MockObject $customerRepository;
    private CustomerValidator $customerValidator;

    #[Group('unit')]
    public function test_validate_customer_exists_does_not_throw_when_customer_exists(): void
    {
        // GIVEN
        $customerId = CustomerId::generate();

        $this->customerRepository
            ->expects($this->once())
            ->method('existsById')
            ->with($customerId)
            ->willReturn(true);

        // WHEN
        $this->customerValidator->validateCustomerExists($customerId);

        // THEN
        $this->assertTrue(true, 'No exception was launched as expected');
    }

    #[Group('unit')]
    public function test_validate_customer_exists_throws_exception_when_customer_does_not_exist(): void
    {
        // GIVEN
        $customerId = CustomerId::generate();

        $this->customerRepository
            ->expects($this->once())
            ->method('existsById')
            ->with($customerId)
            ->willReturn(false);

        // THEN
        $this->expectException(CustomerNotFoundException::class);
        $this->expectExceptionMessage('Customer <' . $customerId->value() . '> not found.');

        // WHEN
        $this->customerValidator->validateCustomerExists($customerId);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->customerRepository = $this->createMock(CustomerRepositoryInterface::class);
        $this->customerValidator = new CustomerValidator($this->customerRepository);
    }
}

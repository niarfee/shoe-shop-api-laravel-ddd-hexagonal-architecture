<?php

declare(strict_types=1);

namespace Tests\Src\Shop\Customer\Infrastructure\Repository;

use PHPUnit\Framework\Attributes\Group;
use Src\Shop\Customer\Domain\CustomerRepositoryInterface;
use Src\Shop\Customer\Domain\Exception\CustomerNotFoundByEmailException;
use Src\Shop\Customer\Domain\Exception\DuplicateCustomerEmailException;
use Src\Shop\Customer\Infrastructure\Mapper\CustomerMapper;
use Src\Shop\Customer\Infrastructure\Repository\EloquentCustomerRepository;
use Tests\Src\Shop\Customer\Domain\CustomerMother;
use Tests\Src\Shop\Customer\Domain\ValueObject\CustomerEmailMother;
use Tests\Src\Shop\Customer\Domain\ValueObject\CustomerFirstNameMother;
use Tests\Src\Shop\Shared\Domain\ValueObject\CustomerIdMother;
use Tests\Utils\IntegrationDatabaseTransactionsLaravelTestCase;

final class EloquentCustomerRepositoryTest extends IntegrationDatabaseTransactionsLaravelTestCase
{
    private CustomerRepositoryInterface $repository;

    #[Group('integration')]
    public function test_it_should_save_a_customer(): void
    {
        $customer = CustomerMother::make(
            email: CustomerEmailMother::make('timothy99@example.org'),
        );

        $this->repository->save($customer);

        $this->assertDatabaseHas('customers', [
            'email' => 'timothy99@example.org',
        ], $this->connectionShop);
    }

    #[Group('integration')]
    public function test_it_should_update_an_existing_customer(): void
    {
        $customerToCreate = CustomerMother::make(
            firstName: CustomerFirstNameMother::make(), // Any first name
        );
        $this->repository->save($customerToCreate);

        $customerToUpdate = CustomerMother::make(
            id: $customerToCreate->id(),
            email: $customerToCreate->email(), // keep email (unique), change other data
            firstName: CustomerFirstNameMother::make(), // Other first name
        );

        $this->repository->save($customerToUpdate);

        $this->assertDatabaseHas('customers', [
            'id' => $customerToCreate->id()->value(),
            'email' => $customerToCreate->email()->value(),
            'first_name' => $customerToUpdate->firstName()->value(),
        ], $this->connectionShop);
    }

    #[Group('integration')]
    public function test_it_should_throw_duplicate_email_exception_on_duplicate(): void
    {
        $firstCustomer = CustomerMother::make();
        $secondCustomer = CustomerMother::make(email: $firstCustomer->email());

        $this->repository->save($firstCustomer);

        $this->expectException(DuplicateCustomerEmailException::class);
        $this->repository->save($secondCustomer); // Duplicated
    }

    #[Group('integration')]
    public function test_it_should_find_a_customer_by_email(): void
    {
        $customer = CustomerMother::make();

        $this->repository->save($customer);

        $found = $this->repository->findByEmail($customer->email());

        $this->assertEquals($customer->email(), $found->email());
        $this->assertEquals($customer->firstName(), $found->firstName());
        $this->assertEquals($customer->lastName(), $found->lastName());
    }

    #[Group('integration')]
    public function test_it_throws_exception_when_customer_not_found_by_email(): void
    {
        $this->expectException(CustomerNotFoundByEmailException::class);

        $this->repository->findByEmail(
            CustomerEmailMother::make('nonexistent@example.com'),
        );
    }

    #[Group('integration')]
    public function test_it_should_check_existence_by_id(): void
    {
        $customer = CustomerMother::make();
        $this->repository->save($customer);

        $this->assertTrue($this->repository->existsById($customer->id()));
        $this->assertFalse($this->repository->existsById(CustomerIdMother::make()));
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new EloquentCustomerRepository(new CustomerMapper());
    }
}

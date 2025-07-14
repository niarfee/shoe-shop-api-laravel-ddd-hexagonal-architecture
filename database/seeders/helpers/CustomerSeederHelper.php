<?php

declare(strict_types=1);

namespace Database\Seeders\Helpers;

use Src\Shop\Customer\Infrastructure\Persistence\Eloquent\CustomerEloquentModel;
use Tests\Src\Shop\Customer\Domain\CustomerMother;

class CustomerSeederHelper extends SeederHelper
{
    public function buildFake(int $quantity): array
    {
        // return CustomerEloquentModel::factory()->count($quantity)->make()->toArray();

        $customers = [];

        foreach (range(1, $quantity) as $_) {
            $customers[] = CustomerMother::make();
        }

        return $customers;
    }

    public function fromDomainListToArrayList(array $customers): array
    {
        $customersArray = [];

        foreach ($customers as $customer) {
            $customersArray[] = [
                'id' => $customer->id()->value(),
                'first_name' => $customer->firstName()->value(),
                'last_name' => $customer->lastName()->value(),
                'email' => $customer->email()->value(),
            ];
        }

        return $customersArray;
    }

    public function persistList(array $customers): void
    {
        parent::persistListBase(CustomerEloquentModel::class, $customers);
    }
}

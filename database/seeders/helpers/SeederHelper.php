<?php

declare(strict_types=1);

namespace Database\Seeders\Helpers;

use Src\Shared\Domain\TransactionalInterface;

abstract class SeederHelper
{
    public function __construct(
        protected readonly TransactionalInterface $transactional,
    ) {
    }

    protected function persistListBase(string $modelClass, array $items): void
    {
        foreach ($items as $item) {
            $modelClass::create($item);
        }
    }

    protected function persistListWithSubModelBase(
        string $modelClass,
        string $subModelClass,
        array $items,
        string $keySubModel,
        string $keyFk,
    ): void {
        foreach ($items as $item) {
            $this->transactional->run(function () use ($modelClass, $subModelClass, $item, $keySubModel, $keyFk) {
                $subModelItems = $item[$keySubModel] ?? [];
                unset($item[$keySubModel]);

                $itemEloquent = $modelClass::create($item);

                foreach ($subModelItems as $subModelItem) {
                    $subModelItem[$keyFk] = $itemEloquent->getKey();
                    $subModelClass::create($subModelItem);
                }
            });
        }
    }
}

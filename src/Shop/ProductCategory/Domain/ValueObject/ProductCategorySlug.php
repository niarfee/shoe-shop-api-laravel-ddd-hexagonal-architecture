<?php

declare(strict_types=1);

namespace Src\Shop\ProductCategory\Domain\ValueObject;

use Src\Shared\Domain\Utils\StringUtils;
use Src\Shared\Domain\ValueObject\StringValueObject;
use Src\Shop\ProductCategory\Domain\Exception\InvalidProductCategorySlugException;

final class ProductCategorySlug extends StringValueObject
{
    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    protected function validate(string $value): void
    {
        parent::validate($value);
        if (StringUtils::containsSpecialCharactersExceptUnderscore($value)) {
            throw new InvalidProductCategorySlugException($value);
        }
    }
}

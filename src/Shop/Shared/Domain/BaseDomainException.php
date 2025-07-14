<?php

declare(strict_types=1);

namespace Src\Shop\Shared\Domain;

use DomainException;

abstract class BaseDomainException extends DomainException
{
    protected static function humanizeClassName(string $className): string
    {
        // Extract the name of the class without the namespace
        $shortName = (new \ReflectionClass($className))->getShortName();

        // Remove common suffixes such as “Id”
        // $shortName = preg_replace('/Id$/', '', $shortName);

        // Convert CamelCase to separate words
        return trim(preg_replace('/(?<!^)([A-Z])/', ' $1', $shortName));
    }
}

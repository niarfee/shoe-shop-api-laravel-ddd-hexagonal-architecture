<?php

declare(strict_types=1);

namespace Src\Shop\Product\Domain\Enum;

enum ProductVariantColorEnum: string
{
    case Green = 'Green';
    case Red = 'Red';
    case White = 'White';
    case Black = 'Black';
    case Blue = 'Blue';
    case Ocre = 'Ocre';

    public function hexCode(): string
    {
        return match ($this) {
            self::Green => '#35a627',
            self::Red => '#a62727',
            self::White => '#ffffff',
            self::Black => '#000000',
            self::Blue => '#2760a6',
            self::Ocre => '#a68327',
        };
    }
}

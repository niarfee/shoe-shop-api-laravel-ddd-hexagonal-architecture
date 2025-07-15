<?php

declare(strict_types=1);

namespace Src\Shop\Order\Domain\Enum;

enum OrderStatusEnum: int
{
    case InCart = 1;
    case PendingShipment = 2;
    case Sent = 3;
    case Completed = 4;

    public function label(): string
    {
        return match ($this) {
            self::InCart => 'In Cart',
            self::PendingShipment => 'Pending shipment',
            self::Sent => 'Sent',
            self::Completed => 'Completed',
        };
    }
}

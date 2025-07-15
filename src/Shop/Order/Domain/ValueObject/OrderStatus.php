<?php

declare(strict_types=1);

namespace Src\Shop\Order\Domain\ValueObject;

use Src\Shared\Domain\ValueObject\IntUnsignedValueObject;
use Src\Shop\Order\Domain\Enum\OrderStatusEnum;
use Src\Shop\Order\Domain\Exception\InvalidOrderStatusException;

final class OrderStatus extends IntUnsignedValueObject
{
    // Values

    public const IN_CART = OrderStatusEnum::InCart->value;
    public const PENDING_SHIPMENT = OrderStatusEnum::PendingShipment->value;
    public const SENT = OrderStatusEnum::Sent->value;
    public const COMPLETED = OrderStatusEnum::Completed->value;

    protected readonly int $value;

    public function __construct(int $value)
    {
        $this->validate($value);
        $this->value = $value;
    }

    // Named constructors

    public static function inCart(): self
    {
        return new self(self::IN_CART);
    }

    public static function pendingShipment(): self
    {
        return new self(self::PENDING_SHIPMENT);
    }

    public static function sent(): self
    {
        return new self(self::SENT);
    }

    public static function completed(): self
    {
        return new self(self::COMPLETED);
    }

    // Labels

    public static function inCartLabel(): string
    {
        return OrderStatusEnum::InCart->label();
    }

    public static function pendingShipmentLabel(): string
    {
        return OrderStatusEnum::PendingShipment->label();
    }

    public static function sentLabel(): string
    {
        return OrderStatusEnum::Sent->label();
    }

    public static function completedLabel(): string
    {
        return OrderStatusEnum::Completed->label();
    }

    // Others

    public static function statusValuesAvailable(): array
    {
        return array_map(
            fn (OrderStatusEnum $status) => $status->value,
            OrderStatusEnum::cases(),
        );
    }

    public function label(): string
    {
        return OrderStatusEnum::from($this->value)->label();
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    private function validate(int $value): void
    {
        if (!OrderStatusEnum::tryFrom($value)) {
            throw new InvalidOrderStatusException($value);
        }
    }
}

<?php

declare(strict_types=1);

namespace Tests\Shop\Customer\Domain\ValueObject;

use PHPUnit\Framework\Attributes\Group;
use Src\Shared\Domain\Exception\InvalidStringLengthException;
use Src\Shop\Customer\Domain\ValueObject\CustomerLastName;
use Tests\Utils\UnitPhpUnitTestCase;

final class CustomerLastNameTest extends UnitPhpUnitTestCase
{
    private const VALID_LAST_NAME = 'Doe';
    private const TOO_SHORT_NAME = 'D';
    private const TOO_LONG_NAME = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam auctor, nisl eget ultricies tincidunt, nunc nisl aliquam nunc, quis aliquam nisl nunc quis nisl.';
    private const NAME_WITH_SPACES = '  Doe  ';
    private const NAME_WITH_SPECIAL_CHARS = 'D\'Angelo-Ñuñez';

    #[Group('unit')]
    public function test_create_valid_last_name(): void
    {
        // GIVEN
        $name = self::VALID_LAST_NAME;

        // WHEN
        $customerLastName = new CustomerLastName($name);

        // THEN
        $this->assertSame($name, $customerLastName->value());
    }

    #[Group('unit')]
    public function test_create_last_name_with_spaces_gets_trimmed(): void
    {
        // GIVEN
        $name = self::NAME_WITH_SPACES;
        $expected = trim($name);

        // WHEN
        $customerLastName = new CustomerLastName($name);

        // THEN
        $this->assertSame($expected, $customerLastName->value());
    }

    #[Group('unit')]
    public function test_create_last_name_with_special_characters(): void
    {
        // GIVEN
        $name = self::NAME_WITH_SPECIAL_CHARS;

        // WHEN
        $customerLastName = new CustomerLastName($name);

        // THEN (no exception should be thrown)
        $this->assertSame($name, $customerLastName->value());
    }

    #[Group('unit')]
    public function test_create_too_short_last_name_throws_exception(): void
    {
        // GIVEN
        $this->expectException(InvalidStringLengthException::class);
        $this->expectExceptionMessage(
            'The length must be between <2> and <100> characters',
        );

        // WHEN
        new CustomerLastName(self::TOO_SHORT_NAME);
    }

    #[Group('unit')]
    public function test_create_too_long_last_name_throws_exception(): void
    {
        // GIVEN
        $this->expectException(InvalidStringLengthException::class);
        $this->expectExceptionMessage(
            'The length must be between <2> and <100> characters',
        );

        // WHEN
        new CustomerLastName(self::TOO_LONG_NAME);
    }

    #[Group('unit')]
    public function test_equals_instances(): void
    {
        // GIVEN
        $name1 = new CustomerLastName(self::VALID_LAST_NAME);
        $name2 = new CustomerLastName(self::VALID_LAST_NAME);
        $differentName = new CustomerLastName('Smith');

        // THEN
        $this->assertTrue($name1->equals($name2));
        $this->assertFalse($name1->equals($differentName));
    }
}

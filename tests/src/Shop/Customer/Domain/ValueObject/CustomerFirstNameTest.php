<?php

declare(strict_types=1);

namespace Tests\Shop\Customer\Domain\ValueObject;

use PHPUnit\Framework\Attributes\Group;
use Src\Shared\Domain\Exception\InvalidStringLengthException;
use Src\Shop\Customer\Domain\ValueObject\CustomerFirstName;
use Tests\Utils\UnitPhpUnitTestCase;

final class CustomerFirstNameTest extends UnitPhpUnitTestCase
{
    private const VALID_FIRST_NAME = 'John';
    private const TOO_SHORT_NAME = 'J';
    private const TOO_LONG_NAME = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam auctor, nisl eget ultricies tincidunt, nunc nisl aliquam nunc, quis aliquam nisl nunc quis nisl.';
    private const NAME_WITH_SPACES = '  John  ';
    private const NAME_WITH_SPECIAL_CHARS = 'Jöhn-D\'Angelo';

    #[Group('unit')]
    public function test_create_valid_first_name(): void
    {
        // GIVEN
        $name = self::VALID_FIRST_NAME;

        // WHEN
        $customerFirstName = new CustomerFirstName($name);

        // THEN
        $this->assertSame($name, $customerFirstName->value());
    }

    #[Group('unit')]
    public function test_create_first_name_with_spaces_gets_trimmed(): void
    {
        // GIVEN
        $name = self::NAME_WITH_SPACES;
        $expected = trim($name);

        // WHEN
        $customerFirstName = new CustomerFirstName($name);

        // THEN
        $this->assertSame($expected, $customerFirstName->value());
    }

    #[Group('unit')]
    public function test_create_first_name_with_special_characters(): void
    {
        // GIVEN
        $name = self::NAME_WITH_SPECIAL_CHARS;

        // WHEN
        $customerFirstName = new CustomerFirstName($name);

        // THEN (no exception should be thrown)
        $this->assertSame($name, $customerFirstName->value());
    }

    #[Group('unit')]
    public function test_create_too_short_first_name_throws_exception(): void
    {
        // GIVEN
        $this->expectException(InvalidStringLengthException::class);
        $this->expectExceptionMessage(
            'The length must be between <2> and <100> characters',
        );

        // WHEN
        new CustomerFirstName(self::TOO_SHORT_NAME);
    }

    #[Group('unit')]
    public function test_create_too_long_first_name_throws_exception(): void
    {
        // GIVEN
        $this->expectException(InvalidStringLengthException::class);
        $this->expectExceptionMessage(
            'The length must be between <2> and <100> characters',
        );

        // WHEN
        new CustomerFirstName(self::TOO_LONG_NAME);
    }

    #[Group('unit')]
    public function test_equals_instances(): void
    {
        // GIVEN
        $name1 = new CustomerFirstName(self::VALID_FIRST_NAME);
        $name2 = new CustomerFirstName(self::VALID_FIRST_NAME);
        $differentName = new CustomerFirstName('Jane');

        // THEN
        $this->assertTrue($name1->equals($name2));
        $this->assertFalse($name1->equals($differentName));
    }
}

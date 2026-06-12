<?php

declare(strict_types=1);

namespace Serializer\Tests;

use PHPUnit\Framework\TestCase;
use Serializer\Cast;

final class CastTest extends TestCase
{
    public function test_must_cast_string(): void
    {
        self::assertSame('hello', Cast::string('hello'));
        self::assertSame('42', Cast::string(42));
        self::assertSame('', Cast::string(null));
        self::assertSame('', Cast::string(['not', 'scalar']));
    }

    public function test_must_cast_int(): void
    {
        self::assertSame(42, Cast::int(42));
        self::assertSame(42, Cast::int('42'));
        self::assertSame(0, Cast::int(null));
        self::assertSame(0, Cast::int(['not', 'scalar']));
    }

    public function test_must_cast_float(): void
    {
        self::assertSame(9.5, Cast::float(9.5));
        self::assertSame(9.5, Cast::float('9.5'));
        self::assertSame(0.0, Cast::float(null));
    }

    public function test_must_cast_bool(): void
    {
        self::assertTrue(Cast::bool(true));
        self::assertTrue(Cast::bool(1));
        self::assertTrue(Cast::bool('non-empty'));
        self::assertFalse(Cast::bool(0));
        self::assertFalse(Cast::bool(null));
        self::assertFalse(Cast::bool(''));
    }

    public function test_must_cast_array(): void
    {
        self::assertSame(['a' => 1], Cast::array(['a' => 1]));
        self::assertSame([], Cast::array('not an array'));
        self::assertSame([], Cast::array(null));
    }
}

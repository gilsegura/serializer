<?php

declare(strict_types=1);

namespace Serializer;

final class Cast
{
    public static function string(mixed $value): string
    {
        return is_string($value) ? $value : (string) self::scalar($value);
    }

    public static function int(mixed $value): int
    {
        return is_int($value) ? $value : (int) self::scalar($value);
    }

    public static function float(mixed $value): float
    {
        return is_float($value) ? $value : (float) self::scalar($value);
    }

    public static function bool(mixed $value): bool
    {
        return (bool) $value;
    }

    /**
     * @return array<array-key, mixed>
     */
    public static function array(mixed $value): array
    {
        return is_array($value) ? $value : [];
    }

    private static function scalar(mixed $value): string|int|float|bool
    {
        return is_scalar($value) ? $value : '';
    }
}

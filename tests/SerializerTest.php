<?php

declare(strict_types=1);

namespace Serializer\Tests;

use PHPUnit\Framework\TestCase;
use Serializer\Cast;
use Serializer\SerializableInterface;
use Serializer\Serializer;

final class SerializerTest extends TestCase
{
    public function test_must_serialize_serializable_object(): void
    {
        self::assertSame(
            [
                'class' => Point::class,
                'attributes' => ['x' => 1, 'y' => 2],
            ],
            Serializer::serialize(new Point(1, 2)),
        );
    }

    public function test_must_deserialize_serializable_object(): void
    {
        $point = Serializer::deserialize([
            'class' => Point::class,
            'attributes' => ['x' => 3, 'y' => 4],
        ]);

        self::assertInstanceOf(Point::class, $point);
        self::assertSame(3, $point->x);
        self::assertSame(4, $point->y);
    }

    public function test_must_restore_serialized_object(): void
    {
        $point = new Point(5, 6);

        self::assertEquals(
            $point,
            Serializer::deserialize(
                Serializer::serialize($point),
            ),
        );
    }

    public function test_must_restore_object_with_empty_attributes(): void
    {
        $empty = new EmptyObject();

        self::assertEquals(
            $empty,
            Serializer::deserialize(
                Serializer::serialize($empty),
            ),
        );
    }

    public function test_must_serialize_list_shaped_attributes(): void
    {
        $collection = new NumberCollection(1, 2, 3);

        $serialized = Serializer::serialize($collection);

        self::assertSame([1, 2, 3], $serialized['attributes']);
    }

    public function test_must_restore_list_shaped_object(): void
    {
        $collection = new NumberCollection(7, 8, 9);

        self::assertEquals(
            $collection,
            Serializer::deserialize(
                Serializer::serialize($collection),
            ),
        );
    }
}

/**
 * @implements SerializableInterface<array{
 *     x: int,
 *     y: int
 * }>
 */
final readonly class Point implements SerializableInterface
{
    public function __construct(
        public int $x,
        public int $y,
    ) {
    }

    #[\Override]
    public static function deserialize(array $attributes): static
    {
        return new self(
            $attributes['x'],
            $attributes['y']
        );
    }

    #[\Override]
    public function serialize(): array
    {
        return ['x' => $this->x, 'y' => $this->y];
    }
}

/**
 * @implements SerializableInterface<array{}>
 */
final readonly class EmptyObject implements SerializableInterface
{
    #[\Override]
    public static function deserialize(array $attributes): static
    {
        return new self();
    }

    #[\Override]
    public function serialize(): array
    {
        return [];
    }
}

/**
 * @implements SerializableInterface<array<int>>
 */
final readonly class NumberCollection implements SerializableInterface
{
    /** @var int[] */
    public array $numbers;

    public function __construct(int ...$numbers)
    {
        $this->numbers = $numbers;
    }

    #[\Override]
    public static function deserialize(array $attributes): static
    {
        return new self(...$attributes);
    }

    #[\Override]
    public function serialize(): array
    {
        return $this->numbers;
    }
}

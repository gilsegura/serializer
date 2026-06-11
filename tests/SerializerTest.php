<?php

declare(strict_types=1);

namespace Serializer\Tests;

use PHPUnit\Framework\TestCase;
use Serializer\SerializableInterface;
use Serializer\Serializer;

final class SerializerTest extends TestCase
{
    public function test_must_deserialize_serializable_object(): void
    {
        $serializable = Serializer::deserialize([
            'class' => Serializable::class,
            'attributes' => [],
        ]);

        self::assertInstanceOf(
            Serializable::class,
            $serializable,
        );
    }

    public function test_must_serialize_serializable_object(): void
    {
        self::assertSame(
            [
                'class' => Serializable::class,
                'attributes' => [],
            ],
            Serializer::serialize(new Serializable()),
        );
    }

    public function test_must_restore_serialized_object(): void
    {
        $object = new Serializable();

        self::assertEquals(
            $object,
            Serializer::deserialize(
                Serializer::serialize($object),
            ),
        );
    }
}

final readonly class Serializable implements SerializableInterface
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

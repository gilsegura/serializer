<?php

declare(strict_types=1);

namespace Serializer\Tests;

use PHPUnit\Framework\TestCase;
use Serializer\SerializableInterface;
use Serializer\Serializer;
use Serializer\SerializerException;

final class SerializerTest extends TestCase
{
    public function test_not_must_deserialize_array_when_not_implementing_serializable(): void
    {
        self::expectException(SerializerException::class);

        Serializer::deserialize(['class' => 'stdClass', 'attributes' => ['foo' => 'bar']]);
    }

    public function test_must_deserialize_array_when_implementing_serializable(): void
    {
        $serializable = Serializer::deserialize(['class' => Serializable::class, 'attributes' => []]);

        self::assertInstanceOf(Serializable::class, $serializable);
    }

    public function test_must_serialize_array_when_implementing_serializable(): void
    {
        $serializable = Serializer::serialize(new Serializable());

        self::assertSame([
            'class' => Serializable::class,
            'attributes' => [],
        ], $serializable);
    }
}

final readonly class Serializable implements SerializableInterface
{
    #[\Override]
    public static function deserialize(array $data): self
    {
        return new self();
    }

    #[\Override]
    public function serialize(): array
    {
        return [];
    }
}

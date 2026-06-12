<?php

declare(strict_types=1);

namespace Serializer;

/**
 * Contract for serializable objects.
 */
interface SerializableInterface
{
    /**
     * @param array<array-key, mixed> $attributes
     *
     * @throws \Throwable
     */
    public static function deserialize(array $attributes): static;

    /**
     * @return array<array-key, mixed>
     */
    public function serialize(): array;
}

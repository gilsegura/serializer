<?php

declare(strict_types=1);

namespace Serializer;

/**
 * Contract for serializable objects.
 */
interface SerializableInterface
{
    /**
     * @param array<string, mixed> $attributes
     *
     * @throws \Throwable
     */
    public static function deserialize(array $attributes): static;

    /**
     * @return array<string, mixed>
     */
    public function serialize(): array;
}

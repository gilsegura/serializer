<?php

declare(strict_types=1);

namespace Serializer;

/**
 * Contract for serializable objects.
 *
 * @template TAttributes of array
 */
interface SerializableInterface
{
    /**
     * @param TAttributes $attributes
     *
     * @throws \Throwable
     */
    public static function deserialize(array $attributes): static;

    /**
     * @return TAttributes
     */
    public function serialize(): array;
}
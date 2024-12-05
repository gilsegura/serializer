<?php

declare(strict_types=1);

namespace Serializer;

interface SerializerInterface
{
    /**
     * @throws SerializerException
     */
    public static function deserialize(array $serializedObject): object;

    public static function serialize(SerializableInterface $object): array;
}

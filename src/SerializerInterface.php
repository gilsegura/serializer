<?php

declare(strict_types=1);

namespace Serializer;

use ProxyAssert\Exception\AssertionFailedException;

interface SerializerInterface
{
    /**
     * @throws AssertionFailedException
     */
    public static function deserialize(array $serializedObject): object;

    /**
     * @throws AssertionFailedException
     */
    public static function serialize(SerializableInterface $object): array;
}

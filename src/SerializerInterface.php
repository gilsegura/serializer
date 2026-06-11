<?php

declare(strict_types=1);

namespace Serializer;

interface SerializerInterface
{
    /**
     * @template T of SerializableInterface
     *
     * @param array{
     *     class: class-string<T>,
     *     attributes: array<string, mixed>
     * } $serializedObject
     *
     * @return T
     *
     * @throws \Throwable
     */
    public static function deserialize(array $serializedObject): SerializableInterface;

    /**
     * @template T of SerializableInterface
     *
     * @param T $object
     *
     * @return array{
     *     class: class-string<T>,
     *     attributes: array<string, mixed>
     * }
     */
    public static function serialize(SerializableInterface $object): array;
}

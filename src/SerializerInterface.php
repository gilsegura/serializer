<?php

declare(strict_types=1);

namespace Serializer;

interface SerializerInterface
{
    /**
     * @template TAttributes of array
     * @template TObject of SerializableInterface<TAttributes>
     *
     * @param array{
     *     class: class-string<TObject>,
     *     attributes: TAttributes
     * } $serializedObject
     *
     * @return TObject
     *
     * @throws \Throwable
     */
    public static function deserialize(array $serializedObject): SerializableInterface;

    /**
     * @template TAttributes of array
     * @template TObject of SerializableInterface<TAttributes>
     *
     * @param TObject $object
     *
     * @return array{
     *     class: class-string<TObject>,
     *     attributes: TAttributes
     * }
     */
    public static function serialize(SerializableInterface $object): array;
}
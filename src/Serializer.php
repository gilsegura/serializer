<?php

declare(strict_types=1);

namespace Serializer;

final readonly class Serializer implements SerializerInterface
{
    /** @codeCoverageIgnore */
    private function __construct()
    {
    }

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
    #[\Override]
    public static function deserialize(array $serializedObject): SerializableInterface
    {
        return $serializedObject['class']::deserialize(
            $serializedObject['attributes'],
        );
    }

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
    #[\Override]
    public static function serialize(SerializableInterface $object): array
    {
        return [
            'class' => $object::class,
            'attributes' => $object->serialize(),
        ];
    }
}

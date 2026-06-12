<?php

declare(strict_types=1);

namespace Serializer;

final readonly class Serializer implements SerializerInterface
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
     */
    #[\Override]
    public static function deserialize(array $serializedObject): SerializableInterface
    {
        return $serializedObject['class']::deserialize(
            $serializedObject['attributes'],
        );
    }

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
    #[\Override]
    public static function serialize(SerializableInterface $object): array
    {
        return [
            'class' => $object::class,
            'attributes' => $object->serialize(),
        ];
    }
}
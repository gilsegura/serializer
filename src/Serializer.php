<?php

declare(strict_types=1);

namespace Serializer;

use ProxyAssert\Assertion;

final readonly class Serializer implements SerializerInterface
{
    /** @codeCoverageIgnore */
    private function __construct()
    {
    }

    #[\Override]
    public static function deserialize(array $serializedObject): SerializableInterface
    {
        try {
            Assertion::keyExists($serializedObject, 'class');
            Assertion::keyExists($serializedObject, 'attributes');

            Assertion::implementsInterface($serializedObject['class'], SerializableInterface::class);
        } catch (\Throwable $e) {
            throw SerializerException::throwable($e);
        }

        return $serializedObject['class']::deserialize($serializedObject['attributes']);
    }

    #[\Override]
    public static function serialize(SerializableInterface $object): array
    {
        return [
            'class' => $object::class,
            'attributes' => $object->serialize(),
        ];
    }
}

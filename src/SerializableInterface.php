<?php

declare(strict_types=1);

namespace Serializer;

interface SerializableInterface
{
    public static function deserialize(array $data): self;

    public function serialize(): array;
}

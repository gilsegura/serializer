# SERIALIZER COMPONENT

[![tests](https://github.com/gilsegura/serializer/actions/workflows/tests.yaml/badge.svg)](https://github.com/gilsegura/serializer/actions/workflows/tests.yaml)
[![codecov](https://codecov.io/github/gilsegura/serializer/graph/badge.svg?token=6NM77DMN8O)](https://codecov.io/github/gilsegura/serializer)
[![static analysis](https://github.com/gilsegura/serializer/actions/workflows/static-analysis.yaml/badge.svg)](https://github.com/gilsegura/serializer/actions/workflows/static-analysis.yaml)
[![coding standards](https://github.com/gilsegura/serializer/actions/workflows/coding-standards.yaml/badge.svg)](https://github.com/gilsegura/serializer/actions/workflows/coding-standards.yaml)

A lightweight serializer component for PHP applications.

The component provides a simple contract for serializing and deserializing domain objects while preserving type information through static analysis.

## Features

* PHP 8.4+
* Strong static typing
* PHPStan-friendly templates
* Immutable design
* Framework agnostic
* No runtime reflection
* No exception handling: errors surface to the caller as `\Throwable`
* No external dependencies
* Ideal for DDD, CQRS and Event Sourcing architectures

## Installation

```bash
composer require gilsegura/serializer
```

## Usage

### Creating a serializable object

Implement the `SerializableInterface` contract.

```php
<?php

declare(strict_types=1);

use Serializer\SerializableInterface;

final readonly class UserRegistered implements SerializableInterface
{
    public function __construct(
        public string $id,
        public string $email,
    ) {
    }

    public static function deserialize(array $attributes): static
    {
        return new static(
            $attributes['id'],
            $attributes['email'],
        );
    }

    public function serialize(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
        ];
    }
}
```

### Serializing an object

```php
use Serializer\Serializer;

$event = new UserRegistered(
    id: '1',
    email: 'john.doe@example.com',
);

$serialized = Serializer::serialize($event);
```

Result:

```php
[
    'class' => UserRegistered::class,
    'attributes' => [
        'id' => '1',
        'email' => 'john.doe@example.com',
    ],
]
```

### Deserializing an object

```php
$event = Serializer::deserialize([
    'class' => UserRegistered::class,
    'attributes' => [
        'id' => '1',
        'email' => 'john.doe@example.com',
    ],
]);
```

PHPStan automatically infers:

```php
UserRegistered
```

from the provided `class-string`.

## Validation

The serializer assumes the payload is valid.

The component does not define or handle any exceptions. Validation, and any exception it may raise, is left entirely to each serializable object, because only the object itself knows its serialization contract. Both `deserialize` methods are documented as `@throws \Throwable`, so any error surfaces to the caller unchanged.

Example:

```php
public static function deserialize(array $attributes): static
{
    if (!isset($attributes['id'])) {
        throw new \InvalidArgumentException('Missing "id"');
    }

    if (!isset($attributes['email'])) {
        throw new \InvalidArgumentException('Missing "email"');
    }

    return new static(
        $attributes['id'],
        $attributes['email'],
    );
}
```

## Event Sourcing Example

```php
$storedEvent = [
    'class' => UserRegistered::class,
    'attributes' => [
        'id' => '1',
        'email' => 'john.doe@example.com',
    ],
];

$event = Serializer::deserialize($storedEvent);
```

This makes the component especially useful for:

* Event Sourcing
* CQRS
* Message buses
* Domain events
* Snapshots
* Integration events

## Static Analysis

The component uses PHPStan templates to preserve concrete types during serialization and deserialization.

```php
$userRegistered = Serializer::deserialize([
    'class' => UserRegistered::class,
    'attributes' => [
        'id' => '1',
        'email' => 'john.doe@example.com',
    ],
]);
```

PHPStan infers:

```php
UserRegistered
```

without requiring casts or PHPDoc annotations.

## License

MIT. See [LICENSE](LICENSE).

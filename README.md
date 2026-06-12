# SERIALIZER

[![tests](https://github.com/gilsegura/serializer/actions/workflows/tests.yaml/badge.svg)](https://github.com/gilsegura/serializer/actions/workflows/tests.yaml)
[![codecov](https://codecov.io/github/gilsegura/serializer/graph/badge.svg)](https://codecov.io/github/gilsegura/serializer)
[![static analysis](https://github.com/gilsegura/serializer/actions/workflows/static-analysis.yaml/badge.svg)](https://github.com/gilsegura/serializer/actions/workflows/static-analysis.yaml)
[![coding standards](https://github.com/gilsegura/serializer/actions/workflows/coding-standards.yaml/badge.svg)](https://github.com/gilsegura/serializer/actions/workflows/coding-standards.yaml)

A minimal, framework-agnostic serialization contract for PHP 8.5+.

`gilsegura/serializer` defines a single contract, `SerializableInterface`, that objects implement to convert themselves to and from a plain array, plus a `Serializer` facade that wraps an object together with its class name so it can be restored later.

## Features

* PHP 8.5+
* A single `SerializableInterface` contract: `serialize()` / `deserialize()`
* Generic type support for precise static analysis
* A `Serializer` facade producing a self-describing `{class, attributes}` shape
* No dependencies beyond PHP itself

## Installation

```bash
composer require gilsegura/serializer
```

## The contract

An object becomes serializable by implementing `SerializableInterface`:

```php
use Serializer\SerializableInterface;

/**
 * @implements SerializableInterface<array{
 *     x: int,
 *     y: int
 * }>
 */
final readonly class Point implements SerializableInterface
{
    public function __construct(
        public int $x,
        public int $y,
    ) {
    }

    #[\Override]
    public static function deserialize(array $attributes): static
    {
        return new self(
            $attributes['x'],
            $attributes['y'],
        );
    }

    #[\Override]
    public function serialize(): array
    {
        return [
            'x' => $this->x,
            'y' => $this->y,
        ];
    }
}
```

`serialize()` returns the object's own attributes and `deserialize()` rebuilds it from them.

The generic parameter of `SerializableInterface` describes the serialized representation of the object. This allows static analysis tools such as PHPStan and Psalm to infer the exact structure expected by `deserialize()` and returned by `serialize()`.

For objects without attributes:

```php
/**
 * @implements SerializableInterface<array{}>
 */
final readonly class EmptyObject implements SerializableInterface
{
    #[\Override]
    public static function deserialize(array $attributes): static
    {
        return new self();
    }

    #[\Override]
    public function serialize(): array
    {
        return [];
    }
}
```

Collections can use a list representation instead of an associative shape:

```php
use Serializer\SerializableInterface;

/**
 * @implements SerializableInterface<list<int>>
 */
final readonly class NumberCollection implements SerializableInterface
{
    /** @var list<int> */
    public array $numbers;

    public function __construct(int ...$numbers)
    {
        $this->numbers = $numbers;
    }

    #[\Override]
    public static function deserialize(array $attributes): static
    {
        return new self(...$attributes);
    }

    #[\Override]
    public function serialize(): array
    {
        return $this->numbers;
    }
}
```

## The Serializer facade

`Serializer` wraps an object together with its class name, producing a self-describing structure that can be stored or transported and later restored to the exact same type:

```php
use Serializer\Serializer;

$serialized = Serializer::serialize(new Point(1, 2));

// [
//     'class' => Point::class,
//     'attributes' => [
//         'x' => 1,
//         'y' => 2,
//     ],
// ]

$point = Serializer::deserialize($serialized);
```

Because the class name travels with the data, `deserialize()` returns the original concrete type rather than a generic interface. This makes the format suitable for queues, caches, storage systems, or inter-process communication while preserving type information.

## Static analysis

`SerializableInterface` is generic:

```php
/**
 * @template TAttributes of array
 */
interface SerializableInterface
{
    /**
     * @param TAttributes $attributes
     */
    public static function deserialize(array $attributes): static;

    /**
     * @return TAttributes
     */
    public function serialize(): array;
}
```

This enables static analyzers to validate serialization and deserialization logic across your application and catch shape mismatches at analysis time.

## License

MIT. See [LICENSE](LICENSE).

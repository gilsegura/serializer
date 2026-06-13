# SERIALIZER

[![tests](https://github.com/gilsegura/serializer/actions/workflows/tests.yaml/badge.svg)](https://github.com/gilsegura/serializer/actions/workflows/tests.yaml)
[![codecov](https://codecov.io/github/gilsegura/serializer/graph/badge.svg)](https://codecov.io/github/gilsegura/serializer)
[![static analysis](https://github.com/gilsegura/serializer/actions/workflows/static-analysis.yaml/badge.svg)](https://github.com/gilsegura/serializer/actions/workflows/static-analysis.yaml)
[![coding standards](https://github.com/gilsegura/serializer/actions/workflows/coding-standards.yaml/badge.svg)](https://github.com/gilsegura/serializer/actions/workflows/coding-standards.yaml)

A minimal, framework-agnostic serialization contract for PHP 8.4+.

`gilsegura/serializer` defines a single generic contract, `SerializableInterface`,
that objects implement to convert themselves to and from a plain array, plus a
`Serializer` facade that wraps an object together with its class name so it can
be restored later as its exact concrete type.

## Features

* PHP 8.4+
* A generic `SerializableInterface<TAttributes>` contract: `serialize()` / `deserialize()`
* Per-implementation attribute shapes via `@implements`, fully understood by static analysis
* A `Serializer` facade producing a self-describing `{class, attributes}` structure
* No dependencies beyond PHP itself

## Installation

```bash
composer require gilsegura/serializer
```

## The contract

`SerializableInterface` is generic over the shape of its attributes,
`TAttributes`. Each implementation declares its own concrete shape with an
`@implements` tag, so static analysis knows the exact type of every attribute on
both sides of the round trip:

```php
use Serializer\SerializableInterface;

/**
 * @implements SerializableInterface<array{x: int, y: int}>
 */
final readonly class Point implements SerializableInterface
{
    public function __construct(
        public int $x,
        public int $y,
    ) {
    }

    public static function deserialize(array $attributes): static
    {
        return new self($attributes['x'], $attributes['y']);
    }

    public function serialize(): array
    {
        return ['x' => $this->x, 'y' => $this->y];
    }
}
```

Because the shape is declared in `@implements`, `deserialize()` reads
`$attributes['x']` as a typed `int` with no casts or assertions: the analyser
already knows it is an `int`.

`TAttributes` is constrained to `array`, so an implementation may serialize to a
map (an associative object) or to a list:

```php
/**
 * @implements SerializableInterface<array<int>>
 */
final readonly class NumberCollection implements SerializableInterface
{
    /** @var int[] */
    public array $numbers;

    public function __construct(int ...$numbers)
    {
        $this->numbers = $numbers;
    }

    public static function deserialize(array $attributes): static
    {
        return new self(...$attributes);
    }

    public function serialize(): array
    {
        return $this->numbers;
    }
}
```

## The Serializer facade

`Serializer` wraps an object with its class name, producing a self-describing
structure that can be stored or transported and later restored to the exact same
type:

```php
use Serializer\Serializer;

$serialized = Serializer::serialize(new Point(1, 2));
// ['class' => Point::class, 'attributes' => ['x' => 1, 'y' => 2]]

$point = Serializer::deserialize($serialized);
// Point(1, 2)
```

Because the class name travels with the data, `deserialize()` returns the
original concrete type rather than a generic interface. This makes the format
portable across process boundaries (queues, caches, storage) while staying
type-safe.

## Composing serializable objects

An object's `serialize()` may delegate to nested serializable objects, building
up a tree. Each level declares its own shape, so the whole structure stays typed
end to end. This makes `SerializableInterface` a good fit for value objects,
domain events, and read models that need a stable, self-describing wire format.

## License

MIT. See [LICENSE](LICENSE).
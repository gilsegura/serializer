# SERIALIZER

A minimal, framework-agnostic serialization contract for PHP 8.5+.

`gilsegura/serializer` defines a single contract, `SerializableInterface`, that
objects implement to convert themselves to and from a plain array, plus a
`Serializer` facade that wraps an object together with its class name so it can
be restored later. It also ships `Cast`, a small set of type-narrowing helpers
for reading values out of decoded payloads under strict static analysis.

## Features

* PHP 8.4+
* A single `SerializableInterface` contract: `serialize()` / `deserialize()`
* A `Serializer` facade producing a self-describing `{class, attributes}` shape
* `Cast` helpers for safe, static-analysis-friendly value coercion
* No dependencies beyond PHP itself

## Installation

```bash
composer require gilsegura/serializer
```

## The contract

An object becomes serializable by implementing `SerializableInterface`:

```php
use Serializer\Cast;
use Serializer\SerializableInterface;

final readonly class Point implements SerializableInterface
{
    public function __construct(
        public int $x,
        public int $y,
    ) {
    }

    public static function deserialize(array $attributes): static
    {
        return new self(
            Cast::int($attributes['x'] ?? 0),
            Cast::int($attributes['y'] ?? 0),
        );
    }

    public function serialize(): array
    {
        return ['x' => $this->x, 'y' => $this->y];
    }
}
```

`serialize()` returns the object's own attributes; `deserialize()` rebuilds it
from them. Both sides use `array<array-key, mixed>`, so an object may serialize
to a map (an associative object) or to a list (a collection).

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
original concrete type, not a generic interface. This makes the format portable
across process boundaries (queues, caches, storage) while staying type-safe.

## Cast helpers

When reading values out of a decoded payload, the values are `mixed`. `Cast`
provides narrowing helpers that return a concrete type, keeping strict static
analysis satisfied without scattering manual casts:

```php
use Serializer\Cast;

Cast::string($value); // string
Cast::int($value);    // int
Cast::float($value);  // float
Cast::bool($value);   // bool
Cast::array($value);  // array<array-key, mixed>
```

Each helper returns the value unchanged when it already matches the target type,
and coerces it otherwise, falling back to a sensible empty value for
non-coercible input.

## License

MIT. See [LICENSE](LICENSE).

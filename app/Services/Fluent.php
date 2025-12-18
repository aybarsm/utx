<?php

declare(strict_types=1);

namespace App\Services;
use App\Framework\Support\Arr\IsIterable;
use ArrayIterator;
use IteratorAggregate;
use JsonSerializable;
use Tempest\Support\Arr;
use Tempest\Support\Arr\ArrayInterface;
use Traversable;
use function LaravelIdea\throw_if;

class Fluent implements ArrayInterface, JsonSerializable
{
    use IsIterable;
    public function __construct(
        public array $value = [],
        protected string $file = '',
    )
    {
        if (filled($this->file)) {
            $this->file = resolve_path($this->file);
        }
    }

    public static function make(array $value = [], string $file = ''): static
    {
        return new static($value, $file);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return Arr\get_by_key($this->value, $key, value($default));
    }

    public function set(string $key, mixed $value): static
    {
        Arr\set_by_key($this->value, $key, $value);
        return $this;
    }

    public function fill(array $value): static
    {
        $this->value = array_replace_recursive($this->value, $value);

        return $this;
    }

    public function forget(string|int|array $keys, string|int $prefix = '', string|int $suffix = ''): static
    {
        $keys = Arr\map_iterable($keys, static fn ($key) => data_key($key, $prefix, $suffix));
        Arr\forget_keys($this->value, $keys);
        return $this;
    }

    public function has(string $key): bool
    {
        return is_sentinel($this->get($key, sentinel()));
    }

    public function blank(string $key): bool
    {
        return blank($this->get($key, sentinel()));
    }

    public function filled(string $key): bool
    {
        return ! $this->blank($key);
    }

    public function toArray(): array
    {
        return $this->value;
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

    public function toJson($options = 0): string
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    public function toPrettyJson(int $options = 0): string
    {
        return $this->toJson(JSON_PRETTY_PRINT | $options);
    }

    public function isEmpty(): bool
    {
        return empty($this->value);
    }

    public function isNotEmpty(): bool
    {
        return ! $this->isEmpty();
    }

    public function offsetExists($offset): bool
    {
        return isset($this->value[$offset]);
    }

    public function offsetGet($offset): mixed
    {
        return $this->value($offset);
    }

    public function offsetSet($offset, $value): void
    {
        $this->value[$offset] = $value;
    }

    public function offsetUnset($offset): void
    {
        unset($this->value[$offset]);
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->value);
    }

    public function value(string|int $key, $default = null)
    {
        if (array_key_exists($key, $this->value)) {
            return $this->value[$key];
        }

        return value($default);
    }

    public function __get($key)
    {
        return $this->value($key);
    }

    public function __set($key, $value)
    {
        $this->offsetSet($key, $value);
    }


    public function save(bool $pretty = true): static
    {
        throw_if(
            blank($this->file),
            \InvalidArgumentException::class,
            'File path not set to save the Fluent data.'

        );

        \Tempest\Support\Filesystem\write_json(
            $this->file,
            $this->jsonSerialize(),
            $pretty
        );

        return $this;
    }


}
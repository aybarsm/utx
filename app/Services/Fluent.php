<?php

declare(strict_types=1);

namespace App\Services;
use Tempest\Support\Arr;
class Fluent
{
    public function __construct(
        public array $data = []
    )
    {}

    public function get(string $key, mixed $default = null): mixed
    {
        return Arr\get_by_key($this->data, $key, $default);
    }

    public function set(string $key, mixed $value): static
    {
        Arr\set_by_key($this->data, $key, $value);
        return $this;
    }

    public function forget(string|int|array $keys, string|int $prefix = '', string|int $suffix = ''): static
    {
        $keys = Arr\map_iterable($keys, static fn ($key) => data_key($key, $prefix, $suffix));
        Arr\forget_keys($this->data, $keys);
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


}
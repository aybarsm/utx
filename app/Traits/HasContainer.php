<?php

declare(strict_types=1);

namespace App\Traits;

trait HasContainer
{
    protected static array $__container = [];

    public static function get(string $key, mixed $default): mixed
    {
        if (!static::has($key)) {
            static::$__container[$key] = value($default);
        }

        return static::$__container[$key];
    }

    public static function has(string $key): bool
    {
        throw_if(
            blank($key),
            \InvalidArgumentException::class,
            'Container key cannot be blank.'
        );

        return array_key_exists($key, static::$__container);
    }
}
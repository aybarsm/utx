<?php

declare(strict_types=1);

namespace App\Traits;

trait HasContainer
{
    protected static array $__container = [];

    public static function get(string $key, mixed $default): mixed
    {
        if (!isset(static::$__container[$key])) {
            static::$__container[$key] = value($default);
        }

        return static::$__container[$key];
    }

    public static function has(string $key): bool
    {
        if (blank($key)){
            return true;
        }
    }
}
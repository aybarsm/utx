<?php

declare(strict_types=1);

namespace App\Services\Support;

use App\Services\Support;

class Is
{
    public static function sentinel(mixed $value): bool
    {
        return $value === Support::sentinel();
    }

    public static function blank(mixed $value): bool
    {
        if (static::sentinel($value)) {
            return true;
        }

        if (is_null($value)) {
            return true;
        }

        if (is_string($value)) {
            return trim($value) === '';
        }

        if (is_numeric($value) || is_bool($value)) {
            return false;
        }

        if ($value instanceof \Countable) {
            return count($value) === 0;
        }

        if (is_object($value) && method_exists($value, '__toString')) {
            return trim($value->__toString()) === '';
        }

        return empty($value);
    }

    public static function filled(mixed $value): bool
    {
        return ! static::blank($value);
    }
    public static function any_all(bool $all, mixed $value, bool $strict, mixed ...$of): bool
    {
        $value = value($value);
        $iter = static fn($item) => $item === True;
        $of = arr($of)
            ->flatten()
            ->map(
                static fn (mixed $item) => $strict ? $value === value($item) : $value == value($item)
            );

        return $all ? $of->every($iter) : $of->contains($iter);
    }

    public static function any(mixed $value, bool $strict, mixed ...$of): bool
    {
        return static::any_all(false, $value, $strict, ...$of);
    }

    public static function all(mixed $value, bool $strict, mixed ...$of): bool
    {
        return static::any_all(true, $value, $strict, ...$of);
    }

    public static function phar(): bool
    {
        return \Phar::running(false) !== '';
    }
}
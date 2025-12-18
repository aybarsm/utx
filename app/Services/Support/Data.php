<?php

declare(strict_types=1);

namespace App\Services\Support;

use App\Services\Support;

class Data
{
    public static function path(mixed ...$parts): string
    {
        return Support::path('.', ...$parts);
    }

    public static function value(mixed $value, mixed ...$args): mixed
    {
        return $value instanceof \Closure ? $value(...$args) : $value;
    }

    public static function first(mixed $value, mixed ...$args): mixed
    {
        $value = \Tempest\Support\Arr\wrap(value($value, ...$args));
        return value($value[array_key_first($value)]);
    }

    public function default(mixed $returned, mixed $expected, mixed $default, bool $strict = true): mixed
    {
        [$returned, $expected, $default] = [value($returned), value($expected), value($default)];

        if ($strict && $returned === $expected) {
            return $returned;
        }elseif (! $strict && $returned == $expected) {
            return $returned;
        }

        return $default;
    }
    public function key(string|int $key, string|int $prefix = '', string|int $suffix = ''): string
    {
        $cleanup = fn (string|int $item) => str($item)
            ->trim()
            ->trim('.')
            ->replaceRegex('/\.+/', '.')->trim('.')
            ->trim();
        $prefix = $cleanup($prefix)->toString();
        $suffix = $cleanup($suffix)->toString();

        return $cleanup($key)
            ->when(filled($prefix), static fn ($str) => $str->start("{$prefix}."))
            ->when(filled($suffix), static fn ($str) => $str->finish(".{$suffix}"))
            ->toString();
    }
}
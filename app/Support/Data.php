<?php

declare(strict_types=1);

namespace App\Support;

use App\Support;
use App\Support\Arr\ImmutableArray;
use App\Support\Arr\MutableArray;
use App\Support\Str\ImmutableString;

class Data
{
    public static function str(mixed $value): ImmutableString
    {
        return new ImmutableString($value);
    }

    public static function arr(mixed $value): ImmutableArray
    {
        return ImmutableArray::createFrom($value);
    }

    public static function collect(mixed $value): MutableArray
    {
        return MutableArray::createFrom($value);
    }

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
}
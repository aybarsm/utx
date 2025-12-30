<?php

declare(strict_types=1);

use App\Support;

if (!function_exists('path')) {
    function path(...$parts): string
    {
        return Support\Fs::path(...$parts);
    }
}

if (!function_exists('data_path')) {
    function data_path(...$parts): string
    {
        return Support\Data::path(...$parts);
    }
}

if (! function_exists('blank')) {
    function blank(mixed $value): bool
    {
        return Support\Is::blank($value);
    }
}

if (! function_exists('filled')) {
    function filled(mixed $value): bool
    {
        return Support\Is::filled($value);
    }
}

if (! function_exists('str')) {
    function str(mixed $value): Support\Str\ImmutableString
    {
        return Support\Data::str($value);
    }
}

if (! function_exists('arr')) {
    function arr(mixed $value): Support\Arr\ImmutableArray
    {
        return Support\Data::arr($value);
    }
}

if (! function_exists('collect')) {
    function collect(mixed $value): Support\Arr\MutableArray
    {
        return Support\Data::collect($value);
    }
}

if (! function_exists('value')) {
    function value(mixed $value, mixed ...$args): mixed
    {
        return Support\Data::value($value, ...$args);
    }
}

if (! function_exists('first')) {
    function first(mixed $value, mixed ...$args): mixed
    {
        return Support\Data::first($value, ...$args);
    }
}

if (! function_exists('when')) {
    function when(mixed $condition, mixed $value, mixed $default = null): mixed
    {
        return Support::when($condition, $value, $default);
    }
}

if (! function_exists('throw_if')) {
    function throw_if($condition, $exception = 'RuntimeException', ...$parameters)
    {
        return Support::throw_if($condition, $exception, $parameters);
    }
}

if (! function_exists('with')) {
    function with(mixed $value, callable $callback): mixed
    {
        return Support::with($value, $callback);
    }
}

if (! function_exists('tap')) {
    function tap(mixed $value, callable $callback): mixed
    {
        return Support::tap($value, $callback);
    }
}

if (! function_exists('box')) {
    function box(\Closure $callback): array
    {
        return Support::box($callback);
    }
}

if (!function_exists('sentinel')) {
    function sentinel(): string
    {
        return Support::sentinel();
    }
}

if (!function_exists('is_sentinel')) {
    function is_sentinel(mixed $value): bool
    {
        return Support\Is::sentinel(value($value));
    }
}

//if (! function_exists('terminating')) {
//    function terminating(callable $callback): void
//    {
//
//    }
//}
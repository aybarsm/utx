<?php

declare(strict_types=1);

use App\Services\Support;
use App\Framework\Support\Arr\ImmutableArray;
use App\Framework\Support\Str\ImmutableString;

if (!function_exists('path')) {
    function path(...$parts): string
    {
        return Support\Fs::path(...$parts);
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
    function str(mixed $value): ImmutableString
    {
        return new ImmutableString($value);
    }
}

if (! function_exists('arr')) {
    function arr(mixed $value): ImmutableArray
    {
        return ImmutableArray::createFrom($value);
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
        return $callback(value($value));
    }
}

if (! function_exists('tap')) {
    function tap(mixed $value, callable $callback): mixed
    {
        return \Tempest\Support\tap(value($value), $callback);
    }
}

if (! function_exists('box')) {
    function box(\Closure $callback): array
    {
        return \Tempest\Support\box($callback);
    }
}

if (! function_exists('terminating')) {
    function terminating(callable $callback): void
    {

    }
}
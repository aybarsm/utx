<?php

declare(strict_types=1);

namespace App\Services;

use App\Framework\Support\Str\ImmutableString;

class Support
{
    public static function path(string $separator, string $delimiter = '#', array $parts = []): string
    {
        $pattern = str($separator)
            ->toMutableString()
            ->when(
                $delimiter !== '#',
                static fn (ImmutableString $str) => $str->regexEscape($delimiter)
            )
            ->start($delimiter)
            ->finish($delimiter)
            ->toString();

        return arr($parts)
            ->toMutableArray()
            ->map(static fn ($part) => trim(trim(trim($part), $separator)))
            ->map(static fn ($part) => preg_split($pattern, $part, -1, PREG_SPLIT_NO_EMPTY))
            ->flatten()
            ->filter(static fn ($part) => filled($part))
            ->implode($separator)
            ->toString();
    }

    public static function when(mixed $condition, mixed $value, mixed $default = null): mixed
    {
        $condition = $condition instanceof \Closure ? $condition() : $condition;

        if ($condition) {
            return value($value, $condition);
        }

        return value($default, $condition);
    }

    public static function throw_if($condition, $exception = 'RuntimeException', ...$parameters)
    {
        if ($condition) {
            if ($exception instanceof \Closure) {
                $exception = $exception(...$parameters);
            }

            if (is_string($exception) && class_exists($exception)) {
                $exception = new $exception(...$parameters);
            }

            throw is_string($exception) ? new \RuntimeException($exception) : $exception;
        }

        return $condition;
    }
}
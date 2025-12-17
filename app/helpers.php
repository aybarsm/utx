<?php
if (!function_exists('sentinel')) {
    function sentinel(): string
    {
        if (! defined('UTX_SENTINEL')){
            $parts = [
                (string)hrtime(true),
                uniqid(\Tempest\Support\Random\secure_string(64), true),
            ];
            define('UTX_SENTINEL', password_hash(implode('|', $parts), PASSWORD_DEFAULT));
        }
        return UTX_SENTINEL;
    }
}

if (!function_exists('is_sentinel')) {
    function is_sentinel(mixed $value): bool
    {
        return $value === UTX_SENTINEL;
    }
}

if (!function_exists('join_paths')) {
    function join_paths(string $baseDir, ...$paths): string
    {
        return implode(DIRECTORY_SEPARATOR,
            array_map(
                static fn (string $path): string => trim(trim(trim($path), DIRECTORY_SEPARATOR)),
                array_merge([$baseDir], $paths)
            )
        );
    }
}

if (! function_exists('blank')) {
    function blank(mixed $value): bool
    {
        if ($value === sentinel()) {
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

        if ($value instanceof Countable) {
            return count($value) === 0;
        }

        if ($value instanceof \Tempest\Support\Str\ImmutableString || $value instanceof \Tempest\Support\Str\MutableString) {
            return trim($value->toString()) === '';
        }

        return empty($value);
    }
}

if (! function_exists('filled')) {
    function filled(mixed $value): bool
    {
        return ! blank($value);
    }
}

if (! function_exists('str')) {
    function str(mixed $value): \Tempest\Support\Str\ImmutableString
    {
        return \Tempest\Support\str($value);
    }
}

if (! function_exists('arr')) {
    function arr(mixed $value): \Tempest\Support\Arr\ImmutableArray
    {
        return \Tempest\Support\arr($value);
    }
}

if (! function_exists('data_key')) {
    function data_key(string|int $key, string|int $prefix = '', string|int $suffix = ''): string
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
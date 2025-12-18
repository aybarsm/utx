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

if (!function_exists('resolve_path')) {
    function resolve_path(...$parts): string
    {
        $isWindows = null;
        $ret = arr($parts)
            ->pipe(function (\App\Framework\Support\Arr\ImmutableArray $arr) use(&$isWindows) {
                if ($isWindows = (strtolower(PHP_OS_FAMILY) == 'windows')){
                    return $arr;
                }

                if (str_starts_with(trim($arr->first()), '.')){
                    return $arr->put(0, getcwd());
                }elseif (str_starts_with(trim($arr->first()), '~') && ($_SERVER['HOME'] ?? null)){
                    return $arr->put($_SERVER['HOME'], getcwd());
                }

                return $arr;
            })
            ->map(static fn ($part) => trim(trim(trim($part), DIRECTORY_SEPARATOR)))
            ->map(static fn ($part) => preg_split('#' . DIRECTORY_SEPARATOR . '#', $part, -1, PREG_SPLIT_NO_EMPTY))
            ->flatten()
            ->implode(DIRECTORY_SEPARATOR);

        return str($ret)
            ->when(! $isWindows, fn ($path) => $path->start(DIRECTORY_SEPARATOR))
            ->toString();
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
    function arr(mixed $value): \App\Framework\Support\Arr\ImmutableArray
    {
        return \App\Framework\Support\Arr\ImmutableArray::createFrom($value);
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

if (! function_exists('is_phar')) {
    function is_phar(): bool
    {
        return \Phar::running(false) !== '';
    }
}

if (! function_exists('is_phar')) {
    function is_phar(): bool
    {
        return \Phar::running(false) !== '';
    }
}

if (! function_exists('temp_path')) {
    function temp_path(...$parts): string
    {
        return resolve_path(sys_get_temp_dir(), ...$parts);
    }
}

if (! function_exists('value')) {
    function value($value, ...$args): mixed
    {
        return $value instanceof Closure ? $value(...$args) : $value;
    }
}

if (! function_exists('when')) {
    function when($condition, $value, $default = null): mixed
    {
        $condition = $condition instanceof Closure ? $condition() : $condition;

        if ($condition) {
            return value($value, $condition);
        }

        return value($default, $condition);
    }
}
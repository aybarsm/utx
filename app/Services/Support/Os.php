<?php

declare(strict_types=1);

namespace App\Services\Support;

use App\Services\Support;
use App\Services\Utx;
class Os
{
    public static function os(): string
    {
        return Utx::get('os.os', static fn() => strtolower(PHP_OS));
    }

    public static function fam(): string
    {
        return Utx::get('os.fam', static fn() => strtolower(PHP_OS_FAMILY));
    }

    public static function env(string $key, mixed $default = false): mixed
    {
        if (blank($key)){
            return value($default);
        }

        $ret = Utx::get("os.env.{$key}", static fn() => getenv($key));
        return $ret === false ? value($default) : $ret;
    }
}
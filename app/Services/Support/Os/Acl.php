<?php

declare(strict_types=1);

namespace App\Services\Support\Os;

use App\Services\Support;

class Acl
{
    protected static function posix(string $key, mixed $retrieve, mixed $default, mixed ...$args): mixed
    {
        return Support::get("os.posix.uid", static fn() => extension_loaded('posix') ? value($retrieve, ...$args) : value($default, ...$args));
    }
    public static function uid(): ?int
    {
        return static::posix('uid', static fn() => posix_getuid(), null);
    }

    public static function gid(): ?int
    {
        return static::posix('gid', static fn() => posix_getgid(), null);
    }
}
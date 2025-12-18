<?php

declare(strict_types=1);

namespace App\Services\Support\Os;

use App\Services\Support;

class Is
{
    public static function os(string ...$of): bool
    {
        return Support\Is::any(Support\Os::os(), true, ...$of);
    }

    public static function fam(string ...$of): bool
    {
        return Support\Is::any(Support\Os::fam(), true, ...$of);
    }

    public static function fam_unix(): bool
    {
        return static::fam(...['bsd', 'solaris', 'linux']);
    }
}
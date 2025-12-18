<?php

declare(strict_types=1);

namespace App\Services\Support\Os;
use App\Services\Support;

class Path
{
    public static function root(): string
    {
        return match(true){
            ! Support\Os\Is::fam('windows') => '/',
            Support\Os::env('SystemDrive') !== false => (string)first(Support\Os::env('SystemDrive')),
            ($_SERVER['SystemDrive'] ?? null) !== null => $_SERVER['SystemDrive'],
            default => substr(__FILE__, 0, 2)
        };
    }
}
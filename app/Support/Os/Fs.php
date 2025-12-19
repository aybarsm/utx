<?php

declare(strict_types=1);

namespace App\Support\Os;
use App\Support;
class Fs
{
    public static function root(): string
    {
        return match(true){
            ! Is::fam('windows') => '/',
            Support\Os::env('SystemDrive') !== false and filled(Support\Os::env('SystemDrive')) => (string)first(Support\Os::env('SystemDrive')),
            ($_SERVER['SystemDrive'] ?? null) !== null => $_SERVER['SystemDrive'],
            default => substr(__FILE__, 0, 2)
        };
    }
}
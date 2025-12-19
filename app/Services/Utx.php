<?php

declare(strict_types=1);

namespace App\Services;

use App\Traits\HasContainer;

final class Utx
{
    use HasContainer;

    public static function sentinel(): string
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

    public static function root(): string
    {
        if (! defined('UTX_ROOT')){
            $root = match(true){
                Support\Os::env('UTX_ROOT') !== false && filled(Support\Os::env('UTX_ROOT')) => (string)first(Support\Os::env('UTX_ROOT')),
                Support\Os\Is::fam('bsd', 'solaris', 'linux') && Support\Os\Acl::uid() === 0 => '/etc/utx',
                ($_SERVER['HOME'] ?? null) !== null => $_SERVER['HOME'] . DIRECTORY_SEPARATOR . '.utx',
                default => path(':root')
            };

            define('UTX_ROOT', $root);
        }

        return UTX_ROOT;
    }

    public static function boot(): void
    {
        self::sentinel();
        self::root();
    }
}
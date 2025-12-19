<?php

declare(strict_types=1);

namespace App\Services\Support;

use App\Services\Support;

final class Fs
{
    public static function path(mixed ...$parts): string
    {
        if (blank($parts)) {
            return UTX_ROOT;
        }

        $first = str($parts[0])->toMutableString()->trim();

        [$ltrim, $chopStart, $replace] = match(true){
            $first->startsWith('.') => ['.', null, getcwd()],
            $first->startsWith('~') && ($_SERVER['HOME'] ?? null) => ['~', null, $_SERVER['HOME']],
            $first->startsWith(':') => value(static function() use($first) {
                $base = (clone $first)->toImmutableString()->before(DIRECTORY_SEPARATOR);
                $target = $base->trim()->trim(':')->toString();

                $replace = match($target) {
                    'cwd' => getcwd(),
                    'home' => $_SERVER['HOME'],
                    'tmp' => sys_get_temp_dir(),
                    'app_root' => UTX_APP_ROOT,
                    'app' => UTX_APP_ROOT . DIRECTORY_SEPARATOR . 'app',
                    'root' => UTX_ROOT,
                    default => null,
                };
                return [null, $base->toString(), $replace];
            }),
            default => [null, null, null],
        };

        if ($ltrim && $replace){
            $first->ltrim($ltrim);
        }elseif ($chopStart && $replace){
            $first->chopStart($chopStart);
        }
        if (($ltrim || $chopStart) && $replace){
            $first->append( $replace . DIRECTORY_SEPARATOR);
        }

        $parts[0] = $first->toString();
        $ret = Support::path(DIRECTORY_SEPARATOR, '#', $parts);

        if (! Support\Os\Is::fam('windows')){
            $ret = str($ret)->start(DIRECTORY_SEPARATOR)->toString();
        }

        return $ret;
    }
}
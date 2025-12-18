<?php

declare(strict_types=1);

namespace App\Services\Support;

use App\Services\Support;

class Fs
{
    public static function path(mixed ...$parts): string
    {
        if (blank($parts)) {
            return Support\Os\Path::root();
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
                    'app' => dirname(__FILE__),
                    'root' => dirname(__FILE__, 2),
                    'rwd' => static::pathRwd(),
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
        $ret = Support::path(DIRECTORY_SEPARATOR, '#', ...$parts);

        if (! Support\Os\Is::fam('windows')){
            $ret = str($ret)->start(DIRECTORY_SEPARATOR)->toString();
        }

        return $ret;
    }

    public static function pathRwd(): string
    {
        return match(true){
            Support\Os::env('UTX_ROOT') !== false => (string)first(Support\Os::env('UTX_ROOT')),
            Support\Os\Is::fam('bsd', 'solaris', 'linux') && Support\Os\Acl::uid() === 0 => '/etc/utx',
            ($_SERVER['HOME'] ?? null) !== null => $_SERVER['HOME'] . DIRECTORY_SEPARATOR . '.utx',
            default => path(':root')
        };
    }
}
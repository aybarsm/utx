<?php

declare(strict_types=1);

namespace App\Support;

use App\Support;

final class Fs
{
    public static function root(mixed ...$parts): string
    {
        return self::path(\Tempest\root_path(), ...$parts);
    }

    public static function src(mixed ...$parts): string
    {
        return self::path(\Tempest\src_path(), ...$parts);
    }

    public static function internalStorage(mixed ...$parts): string
    {
        return self::path(\Tempest\internal_storage_path(), ...$parts);
    }

    public static function app(mixed ...$parts): string
    {
        return self::path(\Tempest\internal_storage_path(), 'app', ...$parts);
    }
    public static function path(mixed ...$parts): string
    {
        if (blank($parts)) {
            return Os\Fs::root();
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
                    'root' => self::root(),
                    'app' => self::app(),
                    'internal', 'internal_storage', 'internalStorage' => self::internalStorage(),
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

        if (! Os\Is::fam('windows')){
            $ret = str($ret)->start(DIRECTORY_SEPARATOR)->toString();
        }

        return $ret;
    }
}
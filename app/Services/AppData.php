<?php

namespace App\Services;

use App\Services\Fluent;
use Tempest\Container\Singleton;

#[Singleton(tag: 'app-data')]
final class AppData extends Fluent
{

    public function __construct(array $value = [], string $file = '')
    {
        [$value, $file] = static::resolveArguments();
        parent::__construct($value, $file);
    }

    public static function make(array $value = [], string $file = ''): static
    {
        [$value, $file] = static::resolveArguments();
        return parent::make($value, $file);
    }

    protected static function resolveArguments(): array
    {
        $file = path(\Tempest\root_path(), 'app.json');
        if (\Tempest\Support\Filesystem\exists($file)) {
            $value = \Tempest\Support\Filesystem\read_json($file);
        }else {
            $value = [];
        }
        return [$value, $file];
    }
}
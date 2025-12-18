<?php

namespace App\Services;

use App\Services\Fluent;
use function Tempest\root_path;

class AppData extends Fluent
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
        $file = resolve_path(root_path(), 'app.json');
        if (\Tempest\Support\Filesystem\exists($file)) {
            $value = \Tempest\Support\Filesystem\read_json($file);
        }else {
            $value = [];
        }
        return [$value, $file];
    }
}
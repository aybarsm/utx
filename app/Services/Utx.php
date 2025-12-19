<?php

declare(strict_types=1);

namespace App\Services;

use Tempest\Cache\Cache;
use Tempest\Container\Singleton;

#[Singleton(tag: 'utx')]
final class Utx
{
    public readonly Fluent $data;
    private bool $dataSaved = false;
    public function __construct(
        private Cache $cache,
    ){
        $this->data = Fluent::make($this->cache->has('utx-data') ? $this->cache->get('utx-data') : []);
    }
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

    public static function boot(): void
    {
        self::sentinel();
    }
}
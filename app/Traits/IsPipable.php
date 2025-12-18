<?php

namespace App\Traits;

trait IsPipable
{
    public function pipe(callable $callback): mixed
    {
        return $callback($this);
    }
}
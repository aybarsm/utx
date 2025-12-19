<?php

declare(strict_types=1);

namespace App\Dtos;

final class ValidationRule
{
    public string $name;
    public \Closure $callback;
}
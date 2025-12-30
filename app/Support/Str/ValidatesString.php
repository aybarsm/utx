<?php

declare(strict_types=1);

namespace App\Support\Str;
use App\Traits\HasValidator;
use Tempest\Validation\Rules;

trait ValidatesString
{
    use HasValidator;
    public function isJson(?int $depth = null, ?int $flags = null): bool
    {
        return new Rules\IsJsonString($depth, $flags)->isValid(trim($this->value));
    }

    public function isInt(bool $orNull = false): bool
    {
        return new Rules\IsInteger($orNull)->isValid(trim($this->value));
    }
}
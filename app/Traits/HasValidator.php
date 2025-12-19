<?php

declare(strict_types=1);

namespace App\Traits;

use App\Support;
use Tempest\Validation\Rule;
use Tempest\Validation\Validator;
use Tempest\Validation\Rules;
use Tempest\Support\Arr;
trait HasValidator
{
    public private(set) Validator $validator {
        get => $this->validator ??= \Tempest\get(Validator::class);
    }

    public private(set) array $validated {
        get => $this->validated ??= [];
    }

//    protected function validateValue(mixed $value, \Closure|Rule|string|array $rules, ...$args): bool
//    {
//        $ruleMap = [];
//        $rules = Arr\flatten(Arr\wrap($rules));
//        foreach ($rules as $rule) {
//
//        }
//        $rules = collect()->
//    }
}
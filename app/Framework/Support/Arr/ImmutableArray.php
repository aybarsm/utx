<?php

declare(strict_types=1);

namespace App\Framework\Support\Arr;

use App\Traits\IsPipable;
use Tempest\Support\Arr\ArrayInterface;
use Tempest\Support\Arr\IsIterable;
use Tempest\Support\Conditions\HasConditions;

final class ImmutableArray implements ArrayInterface
{
    use IsIterable;
    use ManipulatesArray;
    use HasConditions;
    use IsPipable;

    public function toMutableArray(): MutableArray
    {
        return new MutableArray($this->value);
    }

    protected function createOrModify(iterable $array): self
    {
        return new self($array);
    }
}

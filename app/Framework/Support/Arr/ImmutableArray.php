<?php

declare(strict_types=1);

namespace App\Framework\Support\Arr;
use App\Traits\IsPipable;
use Tempest\Support\Arr\ManipulatesArray;
use Tempest\Support\Arr\ArrayInterface;

final class ImmutableArray implements ArrayInterface
{
    use IsIterable;
    use ManipulatesArray;
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

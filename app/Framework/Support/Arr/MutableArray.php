<?php

declare(strict_types=1);

namespace App\Framework\Support\Arr;

use App\Traits\IsPipable;
use Tempest\Support\Arr\ManipulatesArray;
use Tempest\Support\Arr\ArrayInterface;
final class MutableArray implements ArrayInterface
{
    use IsIterable;
    use ManipulatesArray;
    use IsPipable;

    public function toImmutableArray(): ImmutableArray
    {
        return new ImmutableArray($this->value);
    }

    public function pull(string|int $key, mixed $default = null): mixed
    {
        return \Tempest\Support\Arr\pull($this->value, $key, $default);
    }

    protected function createOrModify(iterable $array): self
    {
        $this->value = iterator_to_array($array);

        return $this;
    }
}

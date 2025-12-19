<?php

declare(strict_types=1);

namespace App\Support\Str;

use App\Traits\IsPipable;
use Stringable;
use Tempest\Support\Conditions\HasConditions;
use Tempest\Support\Html\HtmlString;
use Tempest\Support\Str\StringInterface;

final class ImmutableString implements StringInterface
{
    use ManipulatesString;
    use ValidatesString;
    use HasConditions;
    use IsPipable;

    public function toMutableString(): MutableString
    {
        return new MutableString($this->value);
    }

    public function toHtmlString(): HtmlString
    {
        return new HtmlString($this->value);
    }

    protected function createOrModify(Stringable|string $string): self
    {
        return new self($string);
    }
}

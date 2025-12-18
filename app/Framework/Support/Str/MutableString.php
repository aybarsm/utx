<?php

declare(strict_types=1);

namespace App\Framework\Support\Str;

use App\Traits\IsPipable;
use Stringable;
use Tempest\Support\Conditions\HasConditions;
use Tempest\Support\Html\HtmlString;
use Tempest\Support\Str\StringInterface;

final class MutableString implements StringInterface
{
    use ManipulatesString;
    use HasConditions;
    use IsPipable;

    public function toImmutableString(): ImmutableString
    {
        return new ImmutableString($this->value);
    }

    public function toHtmlString(): HtmlString
    {
        return new HtmlString($this->value);
    }

    protected function createOrModify(Stringable|string $string): self
    {
        $this->value = (string) $string;

        return $this;
    }
}

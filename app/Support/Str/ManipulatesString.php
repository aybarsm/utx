<?php

declare(strict_types=1);

namespace App\Support\Str;
use Tempest\Support\Str\ManipulatesString as BaseManipulatesString;

trait ManipulatesString
{
    use BaseManipulatesString;
    public function chopStart(string | array $needle): self
    {
        foreach (\Tempest\Support\Arr\wrap($needle) as $n) {
            if ($this->startsWith($n)) {
                return $this->createOrModify(substr($this->value, strlen($n)));
            }
        }

        return $this;
    }

    public function chopEnd(string | array $needle): self
    {
        foreach (\Tempest\Support\Arr\wrap($needle) as $n) {
            if ($this->endsWith($n)) {
                return $this->createOrModify(substr($this->value, 0, -strlen($n)));
            }
        }

        return $this;
    }

    public function regexEscape(?string $delimiter = null): self
    {
        return $this->createOrModify(preg_quote($this->value, $delimiter));
    }
}
<?php

declare(strict_types=1);

namespace App\Support\Str;
use Tempest\Support\Str\ManipulatesString as BaseManipulatesString;

trait ManipulatesString
{
    use BaseManipulatesString;

    public function linesReplace(string $with): self
    {
        return $this->replaceRegex("/((\r?\n)|(\r\n?))/", $with);
    }

    public function linesNormalise(): self
    {
        return $this->linesReplace("\n");
    }

    public function linesRemoveEmpty(): self
    {
        return $this->replaceRegex('/^\s*[\r\n]+|[\r\n]+\s*\z/', '')->replaceRegex('/(\n\s*){2,}/', "\n");
    }

    public function linesSplit(int $limit = -1, int $flags = 0): \App\Support\Arr\MutableArray
    {
        return $this->split("/((\r?\n)|(\r\n?))/", $limit, $flags);
    }

    public function whitespaceSplit(int $limit = -1, int $flags = 0): \App\Support\Arr\MutableArray
    {
        return $this->split('#\s#', $limit, $flags);
    }

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
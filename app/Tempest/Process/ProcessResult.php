<?php

namespace App\Tempest\Process;

use App\Support\Str\ImmutableString;
use Tempest\Process\ProcessResult as TempestProcessResult;
final readonly class ProcessResult
{
    public int $rc;
    public string $out;
    public string $err;
    public ImmutableString $outStr;
    public ImmutableString $errStr;
    public string $hash;
    public function __construct(
        TempestProcessResult $result
    ) {
        $this->hash = spl_object_hash($result);
        $this->rc = $result->exitCode;
        $this->out = $result->output;
        $this->err = $result->errorOutput;
        $this->outStr = str($result->output);
        $this->errStr = str($result->errorOutput);
    }

    public static function make(TempestProcessResult $result): ProcessResult
    {
        return new self($result);
    }

    public function exitCode(): int
    {
        return $this->rc;
    }

    public function successful(): bool
    {
        return $this->rc === 0;
    }

    public function failed(): bool
    {
        return ! $this->successful();
    }

    public function lines(bool $err = false): \App\Support\Arr\MutableArray
    {
        return ($err ? $this->errStr : $this->outStr)
            ->trim()
            ->linesSplit(-1, PREG_SPLIT_NO_EMPTY);
    }

    public function isJson(bool $err = false): bool
    {
        return ($err ? $this->errStr : $this->outStr)->isJson();
    }

    public function fromJson(bool $err = false, bool $asArray = true): null|array|object
    {
        return json_decode(($err ? $this->errStr : $this->outStr)->trim(), $asArray);
    }
}

<?php

declare(strict_types=1);

namespace App\Traits;

use Tempest\Process\PendingProcess;
use Tempest\Process\ProcessExecutor;
use App\Tempest\Process\ProcessResult;
use function Tempest\get;

trait HasProcessExecutor
{
    protected ProcessExecutor $exec;

    /** @var ProcessResult[] */
    protected array $processResults = [];

    protected function processRun(PendingProcess $pending): ProcessResult
    {
        $result = ProcessResult::make($this->processExecutor()->run($pending));
        $this->processResults[] = $result;
        return $result;
    }

    protected function processExecutor(): ProcessExecutor
    {
        if (!isset($this->exec)) {
            $this->exec = get(ProcessExecutor::class);
        }

        return $this->exec;
    }

    public function processResultLast(): ?ProcessResult
    {
        return $this->processResults[count($this->processResults) - 1] ?? null;
    }
}
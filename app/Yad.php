<?php

namespace App;

use Tempest\Console\Console;
use Tempest\Console\ConsoleCommand;
use Tempest\Process\ProcessExecutor;
final class Yad
{
    public function __construct(
        private Console $console,
        private ProcessExecutor $executor,
    ) {
    }

    #[ConsoleCommand(name: 'yad')]
    public function __invoke(): void
    {
        $this->console->success('Done!');
    }

    #[ConsoleCommand]
    public function volume(): void
    {
        // …
    }
}

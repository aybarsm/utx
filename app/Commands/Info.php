<?php

namespace App\Commands;

use Tempest\Console\Console;
use Tempest\Console\ConsoleCommand;

final class Info
{
    public function __construct(
        private Console $console,
    ) {
    }

    #[ConsoleCommand(name: 'info:path-rwd')]
    public function path_rwd(): void
    {
        $this->console->info('RWD Path: ' . path(':rwd'));
    }
}

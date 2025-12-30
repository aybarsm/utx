<?php

namespace App\Commands;

use Tempest\Console\Console;
use Tempest\Console\ConsoleCommand;

final class Ws
{
    public function __construct(
        private Console $console,
    ) {
    }

    #[ConsoleCommand(name: 'ws:server')]
    public function server(): void
    {
        $this->console->success('Done!');
    }
}

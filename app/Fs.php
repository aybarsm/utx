<?php

namespace App;

use Tempest\Console\Console;
use Tempest\Console\ConsoleCommand;

final class Fs
{
    public function __construct(
        private Console $console,
    ) {
    }

    #[ConsoleCommand(name: 'fs:find-in')]
    public function find_in(): void
    {
        $this->console->success('Done!');
    }
}

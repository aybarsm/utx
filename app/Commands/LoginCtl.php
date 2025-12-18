<?php

namespace App\Commands;

use Tempest\Cache\Cache;
use Tempest\Console\Console;
use Tempest\Console\ConsoleCommand;
use Tempest\Process\ProcessExecutor;

final class LoginCtl
{
    public function __construct(
        private Console $console,
        private ProcessExecutor $executor,
        private Cache $cache,
    ) {
    }

    #[ConsoleCommand(name: 'loginctl')]
    public function __invoke(): void
    {
        $this->console->success('Done!');
    }

    #[ConsoleCommand(name: 'loginctl:sessions', description: 'Resolve login controller sessions')]
    public function sessions(): void
    {
        $exec = $this->executor->run('loginctl list-sessions --json=short');
        if ($exec->failed() || Str) {
            return;
        }


    }
}

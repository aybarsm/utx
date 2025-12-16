<?php

namespace App;

use Tempest\Console\Console;
use Tempest\Console\ConsoleCommand;
use Tempest\Process\ProcessExecutor;
use Tempest\Console\Schedule;
use Tempest\Console\Scheduler\Every;
use Tempest\Console\Scheduler\Interval;
use Tempest\Cache\Cache;

final class Loginctl
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

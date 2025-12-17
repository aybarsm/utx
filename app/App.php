<?php

namespace App;

use Tempest\Console\Console;
use Tempest\Console\ConsoleCommand;
use Tempest\Process\ProcessExecutor;
use Tempest\Container\Container;
final class App
{
    public function __construct(
        private Container $container,
        private Console $console,
        private ProcessExecutor $executor,
    ) {
    }

    #[ConsoleCommand(name: 'app')]
    public function __invoke(): void
    {
        $this->console->success('Done!');
    }

    #[ConsoleCommand(name: 'app:update', description: 'Update the app')]
    public function update(): void
    {

    }
}

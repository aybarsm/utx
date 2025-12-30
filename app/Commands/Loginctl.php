<?php

namespace App\Commands;

use App\Dtos\Loginctl\Session;
use Tempest\Console\Console;
use Tempest\Console\ConsoleCommand;
use Tempest\Process\ProcessExecutor;
use function Tempest\get;
use function Tempest\map;

final class Loginctl
{
    public function __construct(
        private Console $console,
        private ProcessExecutor $exec,
    ) {
    }

    #[ConsoleCommand(name: 'loginctl:sessions')]
    public function sessions(): void
    {
//        /** @var \App\Services\Loginctl $sessions */
//        $loginctl = get(tag: 'loginctl');
        $out = $this->exec->run('sudo loginctl list-sessions --json=short')->output;
        dump($out);
//        $sessions = map($this->exec->run('loginctl list-sessions --json=short')->output)
//            ->collection()
//            ->to(Session::class);
//        dump($sessions);
    }
}

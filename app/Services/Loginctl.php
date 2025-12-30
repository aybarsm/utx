<?php

declare(strict_types=1);

namespace App\Services;

use App\Dtos\Loginctl\Session;
use Tempest\Container\Singleton;
use Tempest\Process\ProcessExecutor;
use function Tempest\map;
#[Singleton]
final class Loginctl
{
    public function __construct(
        private ProcessExecutor $exec,
    ){
    }

    public function sessions(): \App\Support\Arr\ImmutableArray
    {
        $sessions = $this->exec->run('sudo loginctl list-sessions --json=short');
        $sessions = json_decode(trim($sessions->output), true);
        foreach ($sessions as $key => $session) {
            $sessions[$key]['details'] = str($this->exec->run("sudo loginctl show-session {$session['session']}")->output)
                ->toKeyValueMap();
        }

        return arr(map($sessions)
            ->collection()
            ->to(Session::class));
    }
}
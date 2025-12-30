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

    public function sessions()
    {
        $output = trim($this->exec->run('sudo loginctl list-sessions --json=short')->output);
        return map(json_decode($output, true))
            ->collection()
            ->to(Session::class);
    }
}
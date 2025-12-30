<?php

declare(strict_types=1);

namespace App\Services;

use App\Dtos\Loginctl\Session;
use Tempest\Container\Singleton;
use Tempest\Process\ProcessExecutor;
use function Tempest\map;
#[Singleton(tag: 'loginctl')]
final class Loginctl
{
    public function __construct(
        private ProcessExecutor $exec,
    ){
    }

    public function sessions()
    {
        return map($this->exec->run('loginctl list-sessions --json=short')->output)
            ->collection()
            ->to(Session::class);
    }
}
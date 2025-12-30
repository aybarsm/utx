<?php

namespace App\Commands;

use Tempest\Console\Console;
use Tempest\Console\ConsoleCommand;
use function Tempest\get;

final class Loginctl
{
    public function __construct(
        private Console $console,
    ) {
    }

    #[ConsoleCommand(name: 'loginctl:sessions')]
    public function sessions(): void
    {
        /** @var \App\Services\Loginctl $sessions */
        $loginctl = get('loginctl');
        dump($loginctl->sessions());
    }
}

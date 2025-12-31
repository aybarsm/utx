<?php

namespace App\Commands;

use App\Services\LoginCtl;
use App\Services\WpCtl;
use Tempest\Console\Console;
use Tempest\Console\ConsoleCommand;
use function Tempest\get;

final class Rpi
{
    protected LoginCtl $loginCtl;
    protected WpCtl $wpCtl;
    public function __construct(
        private Console $console,
    ) {
    }

    #[ConsoleCommand(name: 'rpi:session', description: 'Session Info')]
    public function session(): void
    {
        $session = $this->getLoginCtl()->activeSession('wayland');
        $this->console->writeln("[Session] ID: {$session->session} | User: {$session->user} | Class: {$session->class} | UID: {$session->uid}");
    }

    #[ConsoleCommand(name: 'rpi:volume', description: 'Volume Info', aliases: ['rpi:vol'])]
    public function volume(): void
    {
        $volume = $this->getWpCtl()->volume();
        $muted = $volume->muted ? 'Yes' : 'No';
        $this->console->writeln("[Volume] Level: {$volume->level}% | Muted: {$muted}");
    }

    #[ConsoleCommand(name: 'rpi:volume-up', description: 'Increase Volume', aliases: ['rpi:vol-up'])]
    public function volumeUp(int $step = 1): void
    {
        if (! $this->getWpCtl()->volumeUp($step)) {
            $this->console->error($this->getWpCtl()->processResultLast()->err);
        }
        $this->volume();
    }

    #[ConsoleCommand(name: 'rpi:volume-down',  description: 'Decrease Volume', aliases: ['rpi:vol-down'])]
    public function volumeDown(int $step = 1): void
    {
        if (! $this->getWpCtl()->volumeDown($step)) {
            $this->console->error($this->getWpCtl()->processResultLast()->err);
        }
        $this->volume();
    }

    protected function getWpCtl() : WpCtl
    {
        if (!isset($this->wpCtl)) {
            $this->wpCtl = new WpCtl();
        }

        return $this->wpCtl;
    }

    protected function getLoginCtl() : LoginCtl
    {
        if (!isset($this->wpCtl)) {
            $this->loginCtl = get(LoginCtl::class);
        }

        return $this->loginCtl;
    }
}

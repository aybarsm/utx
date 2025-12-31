<?php

namespace App\Commands;

use App\Services\Loginctl;
use App\Services\WpCtl;
use Tempest\Console\Console;
use Tempest\Console\ConsoleCommand;

final class Raspi
{
    protected WpCtl $wpCtl;
    public function __construct(
        private Console $console,
    ) {
    }

    #[ConsoleCommand(name: 'raspi:volume')]
    public function volume(): void
    {
        $volume = $this->getWpCtl()->volume();
        $muted = $volume->muted ? 'Yes' : 'No';
        $this->console->writeln("[Volume] Level: {$volume->level} | Muted: {$muted}");
    }

    #[ConsoleCommand(name: 'raspi:volume-increase')]
    public function volumeIncrease(int $step = 1): void
    {
        if (! $this->getWpCtl()->increaseVolume($step)) {
            $this->console->error($this->getWpCtl()->processResultLast()->err);
        }
        $this->volume();
    }

    #[ConsoleCommand(name: 'raspi:volume-decrease')]
    public function volumeDecrease(int $step = 1): void
    {
        if (! $this->getWpCtl()->decreaseVolume($step)) {
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
}

<?php

namespace App\Commands;

use App\Services\Loginctl;
use App\Services\Wpctl;
use Tempest\Console\Console;
use Tempest\Console\ConsoleCommand;

final class Raspi
{
    public function __construct(
        private Console $console,
    ) {
    }

    #[ConsoleCommand(name: 'raspi:volume')]
    public function volume(): void
    {
        $volume = new Wpctl()->volume();
        $muted = $volume->muted ? 'Yes' : 'No';
        $this->console->info("[Volume] Level: {$volume->level} | Muted: {$muted}");
    }
}

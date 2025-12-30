<?php

declare(strict_types=1);

namespace App\Services;

use Tempest\Process\ProcessExecutor;
use Tempest\Container\Inject;
use App\Support\Str\ImmutableString;
final class Wpctl
{
    #[Inject]
    private ProcessExecutor $exec;
    #[Inject]
    private Loginctl $loginctl;

    public readonly array $env;
    public function __construct(
        public readonly string $id = '@DEFAULT_AUDIO_SINK@',
    ){
        $session = $this->loginctl->activeSession('wayland');
        $this->env = [
            'XDG_RUNTIME_DIR' => "/run/user/{$session->uid}"
        ];
    }

    public function getVolume(): ?float
    {
        $process = pending_process(
            command: "wpctl get-volume {$this->id}",
            environment: $this->env,
        );

        $process = str($this->exec->run($process)->output)->trim()->afterLast(':')->trim();

        if ($process->endsWith('[MUTED]')) {
            return null;
        }

        return (float)$process->toString();
    }

    public function isMuted(): bool
    {
        return $this->getVolume() !== null;
    }
}
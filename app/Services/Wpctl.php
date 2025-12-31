<?php

declare(strict_types=1);

namespace App\Services;

use Tempest\Process\ProcessExecutor;
use function Tempest\get;

final class Wpctl
{
    private ProcessExecutor $exec;
    private Loginctl $loginCtl;


    public readonly array $env;
    public function __construct(
        public readonly string $id = '@DEFAULT_AUDIO_SINK@',
    ){
        $this->exec = get(ProcessExecutor::class);
        $this->loginCtl = get(Loginctl::class);

        $session = $this->loginCtl->activeSession('wayland');

        $this->env = [
            'XDG_RUNTIME_DIR' => "/run/user/{$session->uid}"
        ];
    }

    public function volume(): object
    {
        $ret = [
            'level' => null,
            'muted' => null,
        ];

        str(
            $this->exec->run(
                pending_process(command: "wpctl get-volume {$this->id}", environment: $this->env)
            )->output
        )
        ->trim()
        ->whitespaceSplit(-1, PREG_SPLIT_NO_EMPTY)
        ->each(static function(string $segment) use (&$ret) {
            if (!isset($ret['level']) && floatval($segment) == $segment){
                $ret['level'] = round(floatval($segment), 2, PHP_ROUND_HALF_UP);
            }elseif(!isset($ret['muted']) && $segment === '[MUTED]'){
                $ret['muted'] = true;
            }
        });

        if (!isset($ret['muted'])) {
            $ret['muted'] = false;
        }

        return (object)$ret;
    }


    public function setVolume(int $value): bool
    {
        if ($value < 0){
            $value = 0;
        }elseif($value > 100){
            $value = 100;
        }

        $value = round($value, 2, PHP_ROUND_HALF_DOWN);

        $process = pending_process(
            command: "wpctl set-volume {$this->id} {$value}",
            environment: $this->env,
        );

        return $this->exec->run($process)->successful();
    }


    public function increaseVolume(int $step = 1): bool
    {
        return $this->setVolume($this->volume()->level + $step);
    }

    public function decreaseVolume(int $step = 1): bool
    {
        return $this->setVolume($this->volume()->level - $step);
    }

    public function mute(?bool $muted = null): ?bool
    {
        $cmd = "wpctl set-mute {$this->id} ";
        $cmd .= match($muted){
            true => '1',
            false => '0',
            default => 'toggle'
        };

        $process = $this->exec->run(pending_process(command: $cmd, environment: $this->env));

        if ($process->failed()) {
            return null;
        }

        return $muted === null ? $this->volume()->muted : $muted;
    }
}
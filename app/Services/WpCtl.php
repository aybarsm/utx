<?php

declare(strict_types=1);

namespace App\Services;

use App\Traits\HasProcessExecutor;
use Tempest\Process\ProcessExecutor;
use function Tempest\get;

final class WpCtl
{
    use HasProcessExecutor;
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
            'percent' => null,
        ];

        $this->processRun(pending_process(command: "wpctl get-volume {$this->id}", environment: $this->env))
        ->outStr
        ->trim()
        ->whitespaceSplit(-1, PREG_SPLIT_NO_EMPTY)
        ->each(static function(string $segment) use (&$ret) {
            if (!isset($ret['level']) && floatval($segment) == $segment){
                $ret['level'] = round(floatval($segment), 2, PHP_ROUND_HALF_UP);
            }elseif(!isset($ret['muted']) && $segment === '[MUTED]'){
                $ret['muted'] = true;
            }
        });

        if (isset($ret['level'])) {
            $ret['percent'] = intval(max(intval($ret['level'] * 100), 100));
        }

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
        $volume = $this->volume();
        dump([
            'Level' => $volume->level,
            'Pct' => $volume->percent,
            'To' => $value,
        ]);

        $process = pending_process(
            command: "wpctl set-volume {$this->id} {$value}",
            environment: $this->env,
        );

        return $this->exec->run($process)->successful();
    }

    public function increaseVolume(int $step = 1): bool
    {
        return $this->setVolume(intval($this->volume()->percent + $step));
    }

    public function decreaseVolume(int $step = 1): bool
    {
        return $this->setVolume(intval($this->volume()->percent - $step));
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
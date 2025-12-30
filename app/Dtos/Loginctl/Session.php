<?php

declare(strict_types=1);

namespace App\Dtos\Loginctl;

final class Session
{
    public function __construct(
        public readonly string $session,
        public readonly int $uid,
        public readonly string $user,
        public readonly string $seat,
        public readonly int $leader,
        public readonly string $class,
        public readonly bool $idle,
        public readonly ?string $tty = null,
        public readonly ?string $since = null,
    ){
    }
}
<?php

declare(strict_types=1);

namespace App\Dtos\Loginctl;

final readonly class Session
{
    public function __construct(
        public string $session,
        public int $uid,
        public string $user,
        public ?string $seat,
        public int $leader,
        public string $class,
        public bool $idle,
        public ?string $tty = null,
        public ?string $since = null,
        public ?object $details = null,
    ){
    }
}
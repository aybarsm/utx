<?php

declare(strict_types=1);

namespace App\Framework\Console;

use App\Services\Utx;
use Tempest\Console\Actions\ExecuteConsoleCommand;
use Tempest\Console\ConsoleConfig;
use Tempest\Console\ExitCodeWasInvalid;
use Tempest\Console\Input\ConsoleArgumentBag;
use Tempest\Container\Container;
use Tempest\Core\Application;
use Tempest\Core\Kernel;
use App\Framework\Core\Tempest;

final readonly class ConsoleApplication implements Application
{
    public function __construct(
        private Container $container,
        private ConsoleArgumentBag $argumentBag,
    ) {}

    /** @param \Tempest\Discovery\DiscoveryLocation[] $discoveryLocations */
    public static function boot(
        array $discoveryLocations = [],
    ): self {
        return with(Tempest::boot(
            root: UTX_ROOT,
            discoveryLocations: $discoveryLocations,
            internalStorage: UTX_ROOT . DIRECTORY_SEPARATOR . '.internal',
        ), static function (Container $container) {
            $application = $container->get(ConsoleApplication::class);
            $consoleConfig = $container->get(ConsoleConfig::class);
            $consoleConfig->name = 'utx';

            return $application;
        });
    }

    public function run(): void
    {
        $exitCode = $this->container->get(ExecuteConsoleCommand::class)($this->argumentBag->getCommandName());

        $exitCode = is_int($exitCode) ? $exitCode : $exitCode->value;

        if ($exitCode < 0 || $exitCode > 255) {
            throw new ExitCodeWasInvalid($exitCode);
        }

        $this->container->get(Kernel::class)->shutdown($exitCode);
    }

}

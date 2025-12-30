<?php

namespace App\Commands;

use App\Dtos\Loginctl\Session;
use Tempest\Console\Console;
use Tempest\Console\ConsoleCommand;
use Tempest\Process\ProcessExecutor;
use function Tempest\get;
use function Tempest\map;

final class Loginctl
{
    public function __construct(
        private Console $console,
        private ProcessExecutor $exec,
        private \App\Services\Loginctl $loginCtl,
    ) {
    }

    #[ConsoleCommand(name: 'loginctl:sessions')]
    public function sessions(): void
    {
        $wayland = $this->loginCtl->activeSession('wayland');
        dump($wayland);
//        $out = '[{"session":"2","uid":1001,"user":"aybarsm","seat":"seat0","leader":1209,"class":"user","tty":null,"idle":false,"since":null},{"session":"3","uid":1001,"user":"aybarsm","seat":null,"leader":1493,"class":"manager","tty":null,"idle":false,"since":null},{"session":"56","uid":1001,"user":"aybarsm","seat":null,"leader":5533,"class":"user","tty":null,"idle":false,"since":null}]';
//        dump(str($out)->isJson());
//        $sessions = $this->loginCtl->sessions();

//        dump($sessions);
//        $cmd = $this->exec->run('/Users/aybarsm/PersonalSync/Coding/php/tempest/utx/dev/json.sh');

//        $sessions = map(json_decode(trim($cmd->output), true))
//            ->collection()
//            ->to(Session::class);
//        dump($sessions);
//        $out = '[{"session":"2","uid":1001,"user":"aybarsm","seat":"seat0","leader":1209,"class":"user","tty":null,"idle":false,"since":null},{"session":"3","uid":1001,"user":"aybarsm","seat":null,"leader":1493,"class":"manager","tty":null,"idle":false,"since":null},{"session":"56","uid":1001,"user":"aybarsm","seat":null,"leader":5533,"class":"user","tty":null,"idle":false,"since":null}]\n';
//        dump(preg_replace('/\\n+/', '--deneme--', trim($out)));
//        trim();
//        dump(str($out)
//            ->trim()
//            ->replaceRegex('#\n#', '--deneme--')
////            ->replaceRegex("/^[ \\t\\n\\r\\x00\\x0B]+|[ \\t\\n\\r\\x00\\x0B]+$/u", '')
////            ->replaceRegex('/(\\\\n)+ $$/u', '')
//            ->toString()
//        );
//        dump(str($out)->replaceRegex('/^\s*[\r\n]+|[\r\n]+\s*\z/', '')->replaceRegex('/(\n\s*){2,}/', "\n")->toString());
//        dump(str($out)->replaceRegex('/^\s*[\r\n]+|[\r\n]+\s*\z/', '')->replaceRegex('/(\n\s*){2,}/', "\n")->toString());
//        $out = json_decode($out, true);
//        dump($out);

//        $sessions = map($out)
//            ->collection()
//            ->to(Session::class);

//        /** @var \App\Services\Loginctl $sessions */
//        $loginctl = get(tag: 'loginctl');
//        $out = $this->exec->run('sudo loginctl list-sessions --json=short')->output;
//        dump($out);
//        $sessions = map($this->exec->run('loginctl list-sessions --json=short')->output)
//        $sessions = map($out)
//            ->collection()
//            ->to(Session::class);
//        dump($sessions);
    }
}

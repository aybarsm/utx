<?php

declare(strict_types=1);

namespace App\Support\Str;

trait TransformStrings
{
    public function split(string $pattern, int $limit = -1, int $flags = 0): \App\Support\Arr\MutableArray
    {
        return collect(preg_split($pattern, $this->value, $limit, $flags));
    }
    public function toKeyValueMap(): object
    {
        $mapped = [];
        $this->linesSplit(-1, PREG_SPLIT_NO_EMPTY)
            ->each(static function ($line) use (&$mapped) {
                $line = str($line);
//                $key = $line->trim()->before('=')->camel()->toString();
                $key = $line->trim()->before('=')->toString();
                $val = $line->trim()->afterFirst('=');
                if ($val->isEmpty()){
                    $val = null;
                }elseif (in_array($val->lower()->toString(), ['true', 'on', 'yes', 'enabled'])) {
                    $val = true;
                }elseif (in_array($val->lower()->toString(), ['false', 'off', 'no', 'disabled'])) {
                    $val = false;
                }elseif ($val->isInt()){
                    $val = (int)$val->toString();
                }else {
                    $val = $val->toString();
                }

                $mapped[$key] = $val;
            });

        return (object)$mapped;
    }
}
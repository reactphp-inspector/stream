<?php

declare(strict_types=1);

namespace ReactInspector\Stream;

use WyriHaximus\Metrics\Counter;
use WyriHaximus\Metrics\Label;
use WyriHaximus\Metrics\Label\Name;
use WyriHaximus\Metrics\Registry;

/** @internal */
final class Bridge
{
    private static Counter $read;
    private static Counter $write;

    public static function read(int $value): void
    {
        self::$read->incrBy($value);
    }

    public static function write(int $value): void
    {
        self::$write->incrBy($value);
    }

    public static function setRegistry(Registry $registry): void
    {
        $counters    = $registry->counter('reactphp_io', 'ReactPHP Stream IO Bytes counter', new Name('kind'));
        self::$read  = $counters->counter(new Label('kind', 'read'));
        self::$write = $counters->counter(new Label('kind', 'write'));
    }
}

<?php declare(strict_types=1);

namespace ReactInspector\Stream;

use ReactInspector\GlobalState;
use WyriHaximus\Metrics\Label;
use WyriHaximus\Metrics\Label\Name;
use WyriHaximus\Metrics\LazyRegistry\Registry as LazyRegistry;
use WyriHaximus\Metrics\Registry;
use function array_key_exists;
use const WyriHaximus\Constants\Numeric\ONE_FLOAT;
use const WyriHaximus\Constants\Numeric\ZERO_FLOAT;
use WyriHaximus\Metrics\Counter;

/**
 * @internal
 */
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

    public static function setRegistry(\WyriHaximus\Metrics\Registry $registry)
    {
        $counters = $registry->counter('reactphp_io', 'ReactPHP Stream IO Bytes counter', new Name('kind'));
        self::$read = $counters->counter(new Label('kind', 'read'));
        self::$write = $counters->counter(new Label('kind', 'write'));
    }
}

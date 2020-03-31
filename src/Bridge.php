<?php declare(strict_types=1);

namespace ReactInspector\Stream;

use function array_key_exists;
use const WyriHaximus\Constants\Numeric\ONE_FLOAT;
use const WyriHaximus\Constants\Numeric\ZERO_FLOAT;

final class Bridge
{
    /** @var array<string, float> */
    private static array $state = [
        'read' => ZERO_FLOAT,
        'write' => ZERO_FLOAT,
    ];

    /**
     * @internal
     */
    public static function clear(): void
    {
        self::$state = [
            'read' => ZERO_FLOAT,
            'write' => ZERO_FLOAT,
        ];
    }

    /** @return array<string, float> */
    public static function get(): array
    {
        return self::$state;
    }

    public static function incr(string $key, float $value = ONE_FLOAT): void
    {
        if (! array_key_exists($key, self::$state)) {
            return;
        }

        self::$state[$key] += $value;
    }
}

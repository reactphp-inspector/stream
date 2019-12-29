<?php declare(strict_types=1);

namespace ReactInspector\Stream;

final class Bridge
{
    private static $state = [
        'read' => 0.0,
        'write' => 0.0,
    ];

    /**
     * @internal
     */
    public static function clear(): void
    {
        self::$state = [
            'read' => 0.0,
            'write' => 0.0,
        ];
    }

    public static function get(): array
    {
        return self::$state;
    }

    public static function incr(string $key, float $value = 1): void
    {
        if (!isset(self::$state[$key])) {
            return;
        }

        self::$state[$key] += $value;
    }
}

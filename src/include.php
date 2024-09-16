<?php

declare(strict_types=1);

// @codeCoverageIgnoreStart
use ReactInspector\GlobalState;
use ReactInspector\Stream\Bridge;
use WyriHaximus\Metrics\LazyRegistry\Registry as LazyRegistry;
use WyriHaximus\Metrics\Registry;

if (! function_exists('React\Stream\fread')) {
    require __DIR__ . '/react-stream.php';

    (static function (): void {
        $lazyRegistry = new LazyRegistry();
        GlobalState::subscribe(static fn (Registry $registry) => $lazyRegistry->register($registry));

        /** @psalm-suppress InternalMethod */
        Bridge::setRegistry($lazyRegistry);
    })();
}

// @codeCoverageIgnoreEnd

<?php

declare(strict_types=1);

namespace ReactInspector\Tests\Stream;

use PHPUnit\Framework\TestCase;
use React\Stream;
use ReactInspector\Stream\Bridge;
use WyriHaximus\Metrics\Configuration;
use WyriHaximus\Metrics\InMemory\Registry;
use WyriHaximus\Metrics\Printer\Prometheus;

use function fclose;
use function fopen;
use function fwrite;
use function rewind;

/** @internal */
final class FunctionsTest extends TestCase
{
    /** @test */
    public function fread(): void
    {
        $registry = new Registry(Configuration::create());
        Bridge::setRegistry($registry);
        $handle = fopen('php://memory', 'a+'); /** @phpstan-ignore-line */
        fwrite($handle, 'abc', 3); /** @phpstan-ignore-line */
        self::assertStringContainsString('reactphp_io_total{kind="read"} 0', $registry->print(new Prometheus()));
        Stream\fread($handle, 3); /** @phpstan-ignore-line */
        self::assertStringContainsString('reactphp_io_total{kind="read"} 0', $registry->print(new Prometheus()));
        rewind($handle); /** @phpstan-ignore-line */
        Stream\fread($handle, 3); /** @phpstan-ignore-line */
        self::assertStringContainsString('reactphp_io_total{kind="read"} 3', $registry->print(new Prometheus()));
        fclose($handle); /** @phpstan-ignore-line */
    }

    public function streamGetContents(): void
    {
        /** @test */
        $registry = new Registry(Configuration::create());
        Bridge::setRegistry($registry);
        $handle = fopen('php://memory', 'a+'); /** @phpstan-ignore-line */
        fwrite($handle, 'abc', 3); /** @phpstan-ignore-line */
        self::assertStringContainsString('reactphp_io_total{kind="read"} 0', $registry->print(new Prometheus()));
        Stream\stream_get_contents($handle, 3); /** @phpstan-ignore-line */
        self::assertStringContainsString('reactphp_io_total{kind="read"} 0', $registry->print(new Prometheus()));
        rewind($handle); /** @phpstan-ignore-line */
        Stream\stream_get_contents($handle, 3); /** @phpstan-ignore-line */
        self::assertStringContainsString('reactphp_io_total{kind="read"} 3', $registry->print(new Prometheus()));
        fclose($handle); /** @phpstan-ignore-line */
    }

    /** @test */
    public function fwrite(): void
    {
        $registry = new Registry(Configuration::create());
        Bridge::setRegistry($registry);
        $handle = fopen('php://memory', 'a+'); /** @phpstan-ignore-line */
        self::assertStringContainsString('reactphp_io_total{kind="write"} 0', $registry->print(new Prometheus()));
        Stream\fwrite($handle, 'abc', 3); /** @phpstan-ignore-line */
        self::assertStringContainsString('reactphp_io_total{kind="write"} 3', $registry->print(new Prometheus()));
        fclose($handle); /** @phpstan-ignore-line */
    }
}

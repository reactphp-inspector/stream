<?php declare(strict_types=1);

namespace ReactInspector\Tests\Stream;

use PHPUnit\Framework\TestCase;
use React\Stream;
use ReactInspector\Stream\Bridge;

/**
 * @internal
 */
final class FunctionsTest extends TestCase
{
    public function testFread(): void
    {
        Bridge::clear();
        $handle = \fopen('php://memory', 'a+');
        \fwrite($handle, 'abc', 3);
        self::assertSame(0.0, Bridge::get()['read']);
        Stream\fread($handle, 3);
        self::assertSame(0.0, Bridge::get()['read']);
        \rewind($handle);
        Stream\fread($handle, 3);
        self::assertSame(3.0, Bridge::get()['read']);
        \fclose($handle);
    }

    public function testStreamGetContents(): void
    {
        Bridge::clear();
        $handle = \fopen('php://memory', 'a+');
        \fwrite($handle, 'abc', 3);
        self::assertSame(0.0, Bridge::get()['read']);
        Stream\stream_get_contents($handle, 3);
        self::assertSame(0.0, Bridge::get()['read']);
        \rewind($handle);
        Stream\stream_get_contents($handle, 3);
        self::assertSame(3.0, Bridge::get()['read']);
        \fclose($handle);
    }

    public function testFwrite(): void
    {
        Bridge::clear();
        $handle = \fopen('php://memory', 'a+');
        self::assertSame(0.0, Bridge::get()['write']);
        Stream\fwrite($handle, 'abc', 3);
        self::assertSame(3.0, Bridge::get()['write']);
        \fclose($handle);
    }
}

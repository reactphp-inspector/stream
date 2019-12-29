<?php declare(strict_types=1);

namespace ReactInspector\Tests\Stream;

use PHPUnit\Framework\TestCase;
use ReactInspector\Stream\Monkey;
use Roave\BetterReflection\Util\Autoload\ClassPrinter\PhpParserPrinter;

/**
 * @internal
 */
final class MonkeyTest extends TestCase
{
    public function testRerouting(): void
    {
        $code = (new PhpParserPrinter())(Monkey::patch(MonkeyPatchTestTarget::class));
        self::assertNotFalse(\strpos($code, '\React\Stream\fwrite'), 'fwrite');
        self::assertNotFalse(\strpos($code, '\React\Stream\fread'), 'fread');
        self::assertNotFalse(\strpos($code, '\React\Stream\stream_get_contents'), 'stream_get_contents');
    }
}

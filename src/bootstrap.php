<?php declare(strict_types=1);

use React\Stream\DuplexResourceStream;
use React\Stream\ReadableResourceStream;
use React\Stream\WritableResourceStream;
use ReactInspector\Stream\Monkey;
use Roave\BetterReflection\Reflector\Exception\IdentifierNotFound;
use Roave\BetterReflection\Util\Autoload\ClassLoader;
use Roave\BetterReflection\Util\Autoload\ClassLoaderMethod\EvalLoader;
use Roave\BetterReflection\Util\Autoload\ClassPrinter\PhpParserPrinter;
use function Safe\define;
use const WyriHaximus\Constants\Boolean\TRUE_;

(static function (): void {
    if (defined('REACTPHP_INSPECTOR_STREAM_BOOTSTRAPPED_AND_MONKEY_PATCHED')) {
        return;
    }

    define('REACTPHP_INSPECTOR_STREAM_BOOTSTRAPPED_AND_MONKEY_PATCHED', TRUE_);

    try {
        (static function (): void {
            $loader = new ClassLoader(new EvalLoader(new PhpParserPrinter()));

            // @codeCoverageIgnoreStart
            if (! class_exists(WritableResourceStream::class, false)) {
                $loader->addClass(Monkey::patch(WritableResourceStream::class));
                class_exists(WritableResourceStream::class);
            }

            // @codeCoverageIgnoreEnd

            // @codeCoverageIgnoreStart
            if (! class_exists(ReadableResourceStream::class, false)) {
                $loader->addClass(Monkey::patch(ReadableResourceStream::class));
                class_exists(ReadableResourceStream::class);
            }

            // @codeCoverageIgnoreEnd

            // @codeCoverageIgnoreStart
            if (! class_exists(DuplexResourceStream::class, false)) {
                $loader->addClass(Monkey::patch(DuplexResourceStream::class));
                class_exists(DuplexResourceStream::class);
            }

            // @codeCoverageIgnoreEnd
        })();
    } catch (IdentifierNotFound $inf) {
        // @ignoreException
    }
})();

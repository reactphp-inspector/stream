<?php

declare(strict_types=1);

use React\Stream\DuplexResourceStream;
use React\Stream\ReadableResourceStream;
use React\Stream\WritableResourceStream;
use ReactInspector\Stream\Monkey;
use ReactInspector\Stream\Patch;
use Roave\BetterReflection\Reflector\Exception\IdentifierNotFound;

use function Safe\define;

use const WyriHaximus\Constants\Boolean\TRUE_;

(static function (): void {
    if (defined('REACTPHP_INSPECTOR_STREAM_BOOTSTRAPPED_AND_MONKEY_PATCHED')) {
        return;
    }

    define('REACTPHP_INSPECTOR_STREAM_BOOTSTRAPPED_AND_MONKEY_PATCHED', TRUE_);

    try {
        (static function (): void {
            // @codeCoverageIgnoreStart
            if (! class_exists(WritableResourceStream::class, false)) {
                Patch::load(Monkey::patch(WritableResourceStream::class));
                class_exists(WritableResourceStream::class);
            }

            // @codeCoverageIgnoreEnd

            // @codeCoverageIgnoreStart
            if (! class_exists(ReadableResourceStream::class, false)) {
                Patch::load(Monkey::patch(ReadableResourceStream::class));
                class_exists(ReadableResourceStream::class);
            }

            // @codeCoverageIgnoreEnd

            // @codeCoverageIgnoreStart
            if (! class_exists(DuplexResourceStream::class, false)) {
                Patch::load(Monkey::patch(DuplexResourceStream::class));
                class_exists(DuplexResourceStream::class);
            }
            // @codeCoverageIgnoreEnd
        })();
    } catch (IdentifierNotFound) {
        // @ignoreException
    }
})();

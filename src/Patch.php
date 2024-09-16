<?php

declare(strict_types=1);

namespace ReactInspector\Stream;

use PhpParser\Node;
use PhpParser\PrettyPrinter;

use function str_replace;

final class Patch
{
    /** @param array<Node> $ast */
    public static function load(array $ast): void
    {
        $code = (new PrettyPrinter\Standard())->prettyPrintFile($ast);
        $code = str_replace('<?php', '', $code);

        /**
         * Sorry PHPStan, core requirement of this package
         *
         * @phpstan-ignore-next-line
         */
        eval($code);
    }
}

<?php declare(strict_types=1);

namespace ReactInspector\Tests\Stream;

use function fread;
use function fwrite;
use function stream_get_contents;
use const STDIN;
use const STDOUT;

final class MonkeyPatchTestTarget
{
    public function bitterbal(): void
    {
        fwrite(STDOUT, 'kroket');
        fread(STDIN, 13);
        stream_get_contents(STDIN);
    }
}

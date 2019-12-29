<?php declare(strict_types=1);

namespace ReactInspector\Tests\Stream;

final class MonkeyPatchTestTarget
{
    public function bitterbal(): void
    {
        \fwrite(\STDOUT, 'kroket');
        \fread(\STDIN, 13);
        \stream_get_contents(\STDIN);
    }
}

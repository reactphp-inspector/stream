<?php

declare(strict_types=1);

namespace React\Stream;

use ReactInspector\Stream\Bridge;

use function is_string;
use function strlen;

/** @param resource $handle */
function fread($handle, int $length): string|false
{
    $data = \fread($handle, $length); /** @phpstan-ignore-line */
    /** @psalm-suppress InternalMethod */
    Bridge::read(strlen($data)); /** @phpstan-ignore-line */

    return $data;
}

/** @param resource $handle */
function fwrite($handle, string $data, int|null $length = null): int|false /** @phpstan-ignore-line */
{
    if ($length === null) {
        $writtenLength = \fwrite($handle, $data);
    } else {
        $writtenLength = \fwrite($handle, $data, $length); /** @phpstan-ignore-line */
    }

    /** @psalm-suppress InternalMethod */
    Bridge::write((int) $writtenLength);

    return $writtenLength;
}

/** @param resource $handle */
function stream_get_contents($handle, int $length): string|false
{
    $data = \stream_get_contents($handle, $length);
    if (is_string($data)) {
        /** @psalm-suppress InternalMethod */
        Bridge::read(strlen($data));
    }

    return $data;
}

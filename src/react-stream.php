<?php declare(strict_types=1);

namespace React\Stream;

use ReactInspector\Stream\Bridge;
use function strlen;

/**
 * @param resource $handle
 *
 * @return string|false
 */
function fread($handle, int $length)
{
    $data = \fread($handle, $length);
    Bridge::incr('read', (float) strlen($data));

    return $data;
}

/**
 * @param resource $handle
 * @param ?int     $length
 *
 * @return int|false
 */
function fwrite($handle, string $data, ?int $length = null)
{
    if ($length === null) {
        $writtenLength = \fwrite($handle, $data);
    } else {
        $writtenLength = \fwrite($handle, $data, $length);
    }

    Bridge::incr('write', (float) $writtenLength);

    return $writtenLength;
}

/**
 * @param resource $handle
 *
 * @return string|false
 */
function stream_get_contents($handle, int $length)
{
    $data = \stream_get_contents($handle, $length);
    Bridge::incr('read', (float) strlen($data));

    return $data;
}

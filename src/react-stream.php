<?php declare(strict_types=1);

namespace React\Stream;

use ReactInspector\Stream\Bridge;

function fread($handle, $length)
{
    $data = \fread($handle, $length);
    Bridge::incr('read', (float)\strlen($data));

    return $data;
}

function fwrite($handle, $data, $length = null)
{
    if ($length === null) {
        $writtenLength = \fwrite($handle, $data);
    } else {
        $writtenLength = \fwrite($handle, $data, $length);
    }
    Bridge::incr('write', (float)$writtenLength);

    return $writtenLength;
}

function stream_get_contents($handle, $length)
{
    $data = \stream_get_contents($handle, $length);
    Bridge::incr('read', (float)\strlen($data));

    return $data;
}

<?php declare(strict_types=1);

namespace ReactInspector\Stream;

use ReactInspector\Config;
use function ApiClients\Tools\Rx\observableFromArray;
use ReactInspector\CollectorInterface;
use ReactInspector\Measurement;
use ReactInspector\Metric;
use ReactInspector\Tag;
use Rx\Observable;

final class IOCollector implements CollectorInterface
{
    public function collect(): Observable
    {
        return observableFromArray([
            new Metric(
                new Config(
                    'reactphp_io',
                    'gauge',
                    ''
                ),
                [
                    new Tag('reactphp_component', 'io'),
                ],
                [
                    new Measurement(
                        Bridge::get()['read'],
                        new Tag('io_kind', 'read'),
                    ),
                    new Measurement(
                        Bridge::get()['write'],
                        new Tag('io_kind', 'write'),
                    ),
                ]
            ),
        ]);
    }

    public function cancel(): void
    {
        // Do nothing
    }
}

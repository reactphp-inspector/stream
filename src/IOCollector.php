<?php declare(strict_types=1);

namespace ReactInspector\Stream;

use ReactInspector\CollectorInterface;
use ReactInspector\Config;
use ReactInspector\Measurement;
use ReactInspector\Measurements;
use ReactInspector\Metric;
use ReactInspector\Tag;
use ReactInspector\Tags;
use Rx\Observable;
use function ApiClients\Tools\Rx\observableFromArray;

final class IOCollector implements CollectorInterface
{
    public function collect(): Observable
    {
        return observableFromArray([
            Metric::create(
                new Config(
                    'reactphp_io',
                    'counter',
                    ''
                ),
                new Tags(
                    new Tag('reactphp_component', 'io'),
                ),
                new Measurements(
                    new Measurement(
                        Bridge::get()['read'],
                        new Tags(
                            new Tag('io_kind', 'read'),
                        )
                    ),
                    new Measurement(
                        Bridge::get()['write'],
                        new Tags(
                            new Tag('io_kind', 'write'),
                        )
                    ),
                )
            ),
        ]);
    }

    public function cancel(): void
    {
        // Do nothing
    }
}

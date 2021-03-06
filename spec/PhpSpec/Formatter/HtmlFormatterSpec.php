<?php

declare(strict_types=1);

namespace spec\PhpSpec\Formatter;

use PhpSpec\IO\IO;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use PhpSpec\Event\ExampleEvent;
use PhpSpec\Formatter\Html\ReportItem;
use PhpSpec\Formatter\Html\ReportItemFactory;
use PhpSpec\Formatter\Presenter\Presenter;
use PhpSpec\Listener\StatisticsCollector;

class HtmlFormatterSpec extends ObjectBehavior
{
    const EVENT_TITLE = 'it works';

    public function let(ReportItemFactory $factory, Presenter $presenter, IO $io, StatisticsCollector $stats)
    {
        $this->beConstructedWith($factory, $presenter, $io, $stats);
    }

    public function it_is_an_event_subscriber()
    {
        $this->shouldHaveType('Symfony\Component\EventDispatcher\EventSubscriberInterface');
    }

    public function it_delegates_the_reporting_to_the_event_type_line_reporter(
        ExampleEvent $event,
        ReportItem $item,
        ReportItemFactory $factory,
        Presenter $presenter
    ) {
        $factory->create($event, $presenter)->willReturn($item);
        $item->write(Argument::any())->shouldBeCalled();
        $this->afterExample($event);
    }
}

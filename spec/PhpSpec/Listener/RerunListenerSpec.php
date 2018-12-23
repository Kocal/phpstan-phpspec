<?php

declare(strict_types=1);

namespace spec\PhpSpec\Listener;

use PhpSpec\Event\SuiteEvent;
use PhpSpec\ObjectBehavior;
use PhpSpec\Process\Prerequisites\PrerequisiteTester;
use PhpSpec\Process\ReRunner;

class RerunListenerSpec extends ObjectBehavior
{
    public function let(ReRunner $reRunner, PrerequisiteTester $suitePrerequisites)
    {
        $this->beConstructedWith($reRunner, $suitePrerequisites);
    }

    public function it_subscribes_to_aftersuite()
    {
        $this->shouldHaveType('Symfony\Component\EventDispatcher\EventSubscriberInterface');
        $this->getSubscribedEvents()->shouldHaveKey('afterSuite');
    }

    public function it_does_not_tell_the_rerunner_to_rerun_if_it_is_not_worth_doing_so(SuiteEvent $suiteEvent, ReRunner $reRunner)
    {
        $suiteEvent->isWorthRerunning()->willReturn(false);

        $this->afterSuite($suiteEvent);

        $reRunner->reRunSuite()->shouldNotHaveBeenCalled();
    }

    public function it_tells_the_rerunner_to_rerun_if_it_is_worth_doing_so(SuiteEvent $suiteEvent, ReRunner $reRunner)
    {
        $suiteEvent->isWorthRerunning()->willReturn(true);

        $this->afterSuite($suiteEvent);

        $reRunner->reRunSuite()->shouldHaveBeenCalled();
    }
}

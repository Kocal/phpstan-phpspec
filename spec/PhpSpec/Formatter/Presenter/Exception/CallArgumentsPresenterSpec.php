<?php

declare(strict_types=1);

namespace spec\PhpSpec\Formatter\Presenter\Exception;

use PhpSpec\Formatter\Presenter\Differ\Differ;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Prophecy\Argument\ArgumentsWildcard;
use Prophecy\Call\Call;
use Prophecy\Exception\Call\UnexpectedCallException;
use Prophecy\Prophecy\MethodProphecy;
use Prophecy\Prophecy\ObjectProphecy;

class CallArgumentsPresenterSpec extends ObjectBehavior
{
    public function let(Differ $differ)
    {
        $this->beConstructedWith($differ);
    }

    public function it_should_return_empty_string_if_there_are_no_method_prophecies(
        UnexpectedCallException $exception,
        ObjectProphecy $objectProphecy
    ) {
        $exception->getObjectProphecy()->willReturn($objectProphecy);
        $exception->getArguments()->shouldBeCalled();
        $exception->getMethodName()->willReturn('method');

        $objectProphecy->getMethodProphecies('method')->willReturn([]);

        $this->presentDifference($exception)->shouldReturn('');
    }

    public function it_should_return_empty_string_if_method_prophecies_all_contain_calls(
        UnexpectedCallException $exception,
        ObjectProphecy $objectProphecy,
        MethodProphecy $prophecy,
        Call $call,
        ArgumentsWildcard $wildcard
    ) {
        $exception->getObjectProphecy()->willReturn($objectProphecy);
        $exception->getArguments()->shouldBeCalled();
        $exception->getMethodName()->willReturn('method');

        $objectProphecy->getMethodProphecies(Argument::any())->willReturn([$prophecy]);
        $objectProphecy->findProphecyMethodCalls('method', $wildcard)->willReturn([$call]);

        $prophecy->getArgumentsWildcard()->willReturn($wildcard);

        $this->presentDifference($exception)->shouldReturn('');
    }

    public function it_should_return_empty_string_if_argument_counts_do_not_match(
        UnexpectedCallException $exception,
        ObjectProphecy $objectProphecy,
        MethodProphecy $prophecy,
        ArgumentsWildcard $wildcard
    ) {
        $exception->getObjectProphecy()->willReturn($objectProphecy);
        $exception->getArguments()->willReturn(['a', 'b']);
        $exception->getMethodName()->shouldBeCalled();

        $objectProphecy->getMethodProphecies(Argument::any())->willReturn([$prophecy]);
        $objectProphecy->findProphecyMethodCalls(Argument::any(), Argument::any())->willReturn([]);

        $prophecy->getArgumentsWildcard()->willReturn($wildcard);
        $wildcard->getTokens()->willReturn(['a']);

        $this->presentDifference($exception)->shouldReturn('');
    }
}

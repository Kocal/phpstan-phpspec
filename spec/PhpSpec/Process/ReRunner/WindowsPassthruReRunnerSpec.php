<?php

declare(strict_types=1);

/*
 * This file is part of PhpSpec, A php toolset to drive emergent
 * design by specification.
 *
 * (c) Marcello Duarte <marcello.duarte@gmail.com>
 * (c) Konstantin Kudryashov <ever.zet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\PhpSpec\Process\ReRunner;

use PhpSpec\ObjectBehavior;
use PhpSpec\Process\Context\ExecutionContext;
use Symfony\Component\Process\PhpExecutableFinder;

class WindowsPassthruReRunnerSpec extends ObjectBehavior
{
    public function let(PhpExecutableFinder $executableFinder, ExecutionContext $executionContext)
    {
        $this->beConstructedThrough('withExecutionContext', [$executableFinder, $executionContext]);
    }

    public function it_is_a_rerunner()
    {
        $this->shouldHaveType('PhpSpec\Process\ReRunner');
    }

    public function it_is_not_supported_when_php_process_is_not_found(PhpExecutableFinder $executableFinder)
    {
        $executableFinder->find()->willReturn(false);

        $this->isSupported()->shouldReturn(false);
    }
}

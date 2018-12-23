<?php

declare(strict_types=1);

namespace spec\PhpSpec\Formatter\Presenter\Differ;

use PhpSpec\Exception\Example\FailureException;
use PhpSpec\ObjectBehavior;

class StringEngineSpec extends ObjectBehavior
{
    public function it_is_a_diff_engine()
    {
        $this->shouldBeAnInstanceOf('PhpSpec\Formatter\Presenter\Differ\DifferEngine');
    }

    public function it_supports_string_values()
    {
        $this->supports('string1', 'string2')->shouldReturn(true);
    }

    public function it_calculates_strings_diff()
    {
        $expected = <<<DIFF
<code>
@@ -1,1 +1,1 @@
<diff-del>-string1</diff-del>
<diff-add>+string2</diff-add>
</code>
DIFF;

        $this->compare('string1', 'string2')->shouldBeEqualRegardlessOfLineEndings($expected);
    }

    public function getMatchers(): array
    {
        return [
            'beEqualRegardlessOfLineEndings' => function ($actual, $expected) {
                $actual = str_replace("\r", '', $actual);
                if ($actual !== $expected) {
                    throw new FailureException('Strings are not equal.');
                }

                return true;
            }
        ];
    }
}

<?php

namespace spec\Pamil\ProphecyCommon\Promise;

use Pamil\ProphecyCommon\Promise\ExactPromise;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Prophecy\Promise\PromiseInterface;
use Prophecy\Prophecy\MethodProphecy;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * @mixin ExactPromise
 *
 * @author Kamil Kokot <kamil@kokot.me>
 */
final class ExactPromiseSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ExactPromise::class);
    }

    function it_is_a_Prophecy_promise()
    {
        $this->shouldImplement(PromiseInterface::class);
    }

    function it_throws_a_bad_method_call_exception_if_there_are_no_promises_left(ObjectProphecy $objectProphecy, MethodProphecy $methodProphecy)
    {
        $this->shouldThrow(\BadMethodCallException::class)->during('execute', [[], $objectProphecy, $methodProphecy]);
    }

    function it_throws_a_bad_method_call_exception_if_trying_to_add_another_promise_after_enabling_unexpected_calls_support()
    {
        $this->willReturn('value1')->andThenReturn('value2')->alsoForUnexpectedCalls();

        $this->shouldThrow(\BadMethodCallException::class)->during('andThenReturn', ['value3']);
    }
}

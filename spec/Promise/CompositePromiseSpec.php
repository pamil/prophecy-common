<?php

namespace spec\Pamil\ProphecyCommon\Promise;

use Pamil\ProphecyCommon\Promise\CompositePromise;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Prophecy\Promise\PromiseInterface;
use Prophecy\Prophecy\MethodProphecy;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * @mixin CompositePromise
 *
 * @author Kamil Kokot <kamil@kokot.me>
 */
final class CompositePromiseSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(CompositePromise::class);
    }

    function it_is_a_Prophecy_promise()
    {
        $this->shouldImplement(PromiseInterface::class);
    }

    function it_throws_a_bad_method_call_exception_if_there_are_no_promises_left(ObjectProphecy $objectProphecy, MethodProphecy $methodProphecy)
    {
        $this->shouldThrow(\BadMethodCallException::class)->during('execute', [[], $objectProphecy, $methodProphecy]);
    }
}

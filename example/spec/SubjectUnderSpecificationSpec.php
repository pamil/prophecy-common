<?php

namespace spec\example\Pamil\ProphecyCommon;

use example\Pamil\ProphecyCommon\Collaborator;
use example\Pamil\ProphecyCommon\SubjectUnderSpecification;
use Pamil\ProphecyCommon\Promise\CompositePromise;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Prophecy\Promise\ReturnPromise;

/**
 * @mixin SubjectUnderSpecification
 *
 * @author Kamil Kokot <kamil@kokot.me>
 */
final class SubjectUnderSpecificationSpec extends ObjectBehavior
{
    function let(Collaborator $collaborator)
    {
        $this->beConstructedWith($collaborator);
    }

    function it_exact_promise_returns_a_value(Collaborator $collaborator)
    {
        $collaborator->invoke()->will(CompositePromise::it()->willReturn('value'));

        $this->invokeCollabolator()->shouldReturn('value');
    }

    function it_exact_promise_returns_a_few_values(Collaborator $collaborator)
    {
        $collaborator->invoke()->will(CompositePromise::it()->willReturn('value1', 'value2'));

        $this->invokeCollabolator()->shouldReturn('value1');
        $this->invokeCollabolator()->shouldReturn('value2');
    }

    function it_exact_promise_throws_an_exception(Collaborator $collaborator)
    {
        $collaborator->invoke()->will(CompositePromise::it()->willThrow(\Exception::class));

        $this->shouldThrow(\Exception::class)->during('invokeCollabolator');
    }

    function it_exact_promise_returns_a_value_and_then_throws_an_exception(Collaborator $collaborator)
    {
        $collaborator->invoke()->will(
            CompositePromise::it()->willReturn('value')->andThenThrow(\Exception::class)
        );

        $this->invokeCollabolator()->shouldReturn('value');
        $this->shouldThrow(\Exception::class)->during('invokeCollabolator');
    }

    function it_exact_promise_returns_a_few_values_and_then_throws_an_exception(Collaborator $collaborator)
    {
        $collaborator->invoke()->will(
            CompositePromise::it()->willReturn('value1', 'value2')->andThenThrow(\Exception::class)
        );

        $this->invokeCollabolator()->shouldReturn('value1');
        $this->invokeCollabolator()->shouldReturn('value2');
        $this->shouldThrow(\Exception::class)->during('invokeCollabolator');
    }

    function it_exact_promise_throws_an_exception_and_then_returns_a_value(Collaborator $collaborator)
    {
        $collaborator->invoke()->will(
            CompositePromise::it()->willThrow(\Exception::class)->andThenReturn('value')
        );

        $this->shouldThrow(\Exception::class)->during('invokeCollabolator');
        $this->invokeCollabolator()->shouldReturn('value');
    }

    function it_exact_promise_throws_an_exception_and_then_returns_a_few_values(Collaborator $collaborator)
    {
        $collaborator->invoke()->will(
            CompositePromise::it()->willThrow(\Exception::class)->willReturn('value1', 'value2')
        );

        $this->shouldThrow(\Exception::class)->during('invokeCollabolator');
        $this->invokeCollabolator()->shouldReturn('value1');
        $this->invokeCollabolator()->shouldReturn('value2');
    }

    function it_exact_promise_throws_two_different_exceptions(Collaborator $collaborator)
    {
        $collaborator->invoke()->will(
            CompositePromise::it()->willThrow(\LogicException::class, \RuntimeException::class)
        );

        $this->shouldThrow(\LogicException::class)->during('invokeCollabolator');
        $this->shouldThrow(\RuntimeException::class)->during('invokeCollabolator');
    }

    function it_exact_promise_performs_a_custom_promise(Collaborator $collaborator)
    {
        $collaborator->invoke()->will(
            CompositePromise::it()->will(new ReturnPromise(['value']))
        );

        $this->invokeCollabolator()->shouldReturn('value');
    }

    function it_exact_promise_performs_a_few_custom_promises(Collaborator $collaborator)
    {
        $collaborator->invoke()->will(
            CompositePromise::it()->will(new ReturnPromise(['value1']), new ReturnPromise(['value2']))
        );

        $this->invokeCollabolator()->shouldReturn('value1');
        $this->invokeCollabolator()->shouldReturn('value2');
    }

    function it_exact_promise_returns_a_value_and_performs_a_custom_promise(Collaborator $collaborator)
    {
        $collaborator->invoke()->will(
            CompositePromise::it()->willReturn('return value')->andThen(new ReturnPromise(['custom promise value']))
        );

        $this->invokeCollabolator()->shouldReturn('return value');
        $this->invokeCollabolator()->shouldReturn('custom promise value');
    }

    function it_exact_promise_executes_the_last_promise_if_called_more_times_than_explicitly_defined(Collaborator $collaborator)
    {
        $collaborator->invoke()->will(
            CompositePromise::it()->willReturn('value1', 'value2')
        );

        $this->invokeCollabolator()->shouldReturn('value1');
        $this->invokeCollabolator()->shouldReturn('value2');
        $this->invokeCollabolator()->shouldReturn('value2');
    }
}

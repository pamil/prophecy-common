<?php

namespace spec\Pamil\ProphecyCommon\Example;

use Pamil\ProphecyCommon\Promise\ExactPromise;
use Pamil\ProphecyCommon\Example\Collaborator;
use Pamil\ProphecyCommon\Example\SubjectUnderSpecification;
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

    function it_is_initializable()
    {
        $this->shouldHaveType(SubjectUnderSpecification::class);
    }

    function it_exact_promise_returns_a_value(Collaborator $collaborator)
    {
        $collaborator->invoke()->will(ExactPromise::it()->willReturn('value'));

        $this->invokeCollabolator()->shouldReturn('value');
    }

    function it_exact_promise_returns_a_few_values(Collaborator $collaborator)
    {
        $collaborator->invoke()->will(ExactPromise::it()->willReturn('value1', 'value2'));

        $this->invokeCollabolator()->shouldReturn('value1');
        $this->invokeCollabolator()->shouldReturn('value2');
    }

    function it_exact_promise_throws_an_exception(Collaborator $collaborator)
    {
        $collaborator->invoke()->will(ExactPromise::it()->willThrow(\Exception::class));

        $this->shouldThrow(\Exception::class)->during('invokeCollabolator');
    }

    function it_exact_promise_returns_a_value_and_then_throws_an_exception(Collaborator $collaborator)
    {
        $collaborator->invoke()->will(
            ExactPromise::it()->willReturn('value')->andThenThrow(\Exception::class)
        );

        $this->invokeCollabolator()->shouldReturn('value');
        $this->shouldThrow(\Exception::class)->during('invokeCollabolator');
    }

    function it_exact_promise_returns_a_few_values_and_then_throws_an_exception(Collaborator $collaborator)
    {
        $collaborator->invoke()->will(
            ExactPromise::it()->willReturn('value1', 'value2')->andThenThrow(\Exception::class)
        );

        $this->invokeCollabolator()->shouldReturn('value1');
        $this->invokeCollabolator()->shouldReturn('value2');
        $this->shouldThrow(\Exception::class)->during('invokeCollabolator');
    }

    function it_exact_promise_throws_an_exception_and_then_returns_a_value(Collaborator $collaborator)
    {
        $collaborator->invoke()->will(
            ExactPromise::it()->willThrow(\Exception::class)->andThenReturn('value')
        );

        $this->shouldThrow(\Exception::class)->during('invokeCollabolator');
        $this->invokeCollabolator()->shouldReturn('value');
    }

    function it_exact_promise_throws_an_exception_and_then_returns_a_few_values(Collaborator $collaborator)
    {
        $collaborator->invoke()->will(
            ExactPromise::it()->willThrow(\Exception::class)->willReturn('value1', 'value2')
        );

        $this->shouldThrow(\Exception::class)->during('invokeCollabolator');
        $this->invokeCollabolator()->shouldReturn('value1');
        $this->invokeCollabolator()->shouldReturn('value2');
    }

    function it_exact_promise_throws_two_different_exceptions(Collaborator $collaborator)
    {
        $collaborator->invoke()->will(
            ExactPromise::it()->willThrow(\LogicException::class, \RuntimeException::class)
        );

        $this->shouldThrow(\LogicException::class)->during('invokeCollabolator');
        $this->shouldThrow(\RuntimeException::class)->during('invokeCollabolator');
    }

    function it_exact_promise_performs_a_custom_promise(Collaborator $collaborator)
    {
        $collaborator->invoke()->will(
            ExactPromise::it()->will(new ReturnPromise(['value']))
        );

        $this->invokeCollabolator()->shouldReturn('value');
    }

    function it_exact_promise_performs_a_few_custom_promises(Collaborator $collaborator)
    {
        $collaborator->invoke()->will(
            ExactPromise::it()->will(new ReturnPromise(['value1']), new ReturnPromise(['value2']))
        );

        $this->invokeCollabolator()->shouldReturn('value1');
        $this->invokeCollabolator()->shouldReturn('value2');
    }

    function it_exact_promise_returns_a_value_and_performs_a_custom_promise(Collaborator $collaborator)
    {
        $collaborator->invoke()->will(
            ExactPromise::it()->willReturn('return value')->andThen(new ReturnPromise(['custom promise value']))
        );

        $this->invokeCollabolator()->shouldReturn('return value');
        $this->invokeCollabolator()->shouldReturn('custom promise value');
    }

    function it_exact_promise_returns_a_value_also_for_unexpected_calls(Collaborator $collaborator)
    {
        $collaborator->invoke()->will(
            ExactPromise::it()->willReturn('value')->alsoForUnexpectedCalls()
        );

        $this->invokeCollabolator()->shouldReturn('value');
        $this->invokeCollabolator()->shouldReturn('value');
    }
}

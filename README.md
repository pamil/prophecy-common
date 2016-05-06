Prophecy Common [![Build Status](https://travis-ci.org/pamil/prophecy-common.png?branch=master)](https://travis-ci.org/pamil/prophecy-common)
===============

Bunch of useful classes to solve some issues I've encountered while using PhpSpec. MIT licensed.

ExactPromise
------------

Allows the same method to throw different exceptions, return and throw exceptions at the same time and limit calls without
using `shouldBeCalledTimes()`.

```php
$collabolator->mockedMethod()->will(
    ExactPromise::it()->willThrow(\LogicException::class)->andThenReturn('value')
);
```

#### Differences

 - `ExactPromise::willReturn()` and `ExactPromise::andThenReturn()` behave the same as `MethodProphecy::willReturn()` 
 (in PhpSpec: `$collabolator->callMethod()->willReturn()`)
 
 - `ExactPromise::willThrow()` and `ExactPromiser::andThenThrow()` behave the same as `MethodProphecy::willThrow()`
 (in PhpSpec: `$collabolator->callMethod()->willThrow()`), but they can receive more than one argument, e.g. 
 `ExactPromise::willThrow(\FirstException::class)->andThenThrow(\SecondException::class)` is equal to
 `ExactPromise::willThrow(\FirstException::class, \SecondException::class)`
 
 - `ExactPromise::will()` and `ExactPromise::andThen()` behave the same as `MethodProphecy::will()`, but they can receive
 more than one argument just as the previous methods

#### Example

```php
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

    function it_exact_promise_executes_the_last_promise_if_called_more_times_than_explicitly_defined(Collaborator $collaborator)
    {
        $collaborator->invoke()->will(
            ExactPromise::it()->willReturn('value1', 'value2')
        );

        $this->invokeCollabolator()->shouldReturn('value1');
        $this->invokeCollabolator()->shouldReturn('value2');
        $this->invokeCollabolator()->shouldReturn('value2');
    }
} 
```

<?php

namespace Pamil\ProphecyCommon\Promise;

use Prophecy\Promise\PromiseInterface;
use Prophecy\Promise\ReturnPromise;
use Prophecy\Promise\ThrowPromise;
use Prophecy\Prophecy\MethodProphecy;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * @author Kamil Kokot <kamil@kokot.me>
 */
final class ExactPromise implements PromiseInterface
{
    /**
     * @var PromiseInterface[]
     */
    private $promises = [];

    /**
     * Whether the last promise should be executed multiple times.
     *
     * @var bool
     */
    private $unexpectedCallsSupport = false;

    /**
     * @return self
     */
    public static function it()
    {
        return new self();
    }

    /**
     * @api
     *
     * @param PromiseInterface ...
     *
     * @return $this
     */
    public function will()
    {
        $this->assertThereIsNoUnexpectedCallsSupportYet();

        foreach (func_get_args() as $promise) {
            $this->promises[] = $promise;
        }

        return $this;
    }

    /**
     * @api
     *
     * @param mixed ...
     *
     * @return $this
     */
    public function willReturn()
    {
        $this->assertThereIsNoUnexpectedCallsSupportYet();

        foreach (func_get_args() as $value) {
            $this->promises[] = new ReturnPromise([$value]);
        }

        return $this;
    }

    /**
     * @api
     *
     * @param string|\Exception ...
     *
     * @return $this
     */
    public function willThrow()
    {
        $this->assertThereIsNoUnexpectedCallsSupportYet();

        foreach (func_get_args() as $exception) {
            $this->promises[] = new ThrowPromise($exception);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function alsoForUnexpectedCalls()
    {
        $this->unexpectedCallsSupport = true;

        return $this;
    }

    /**
     * @api
     *
     * @param PromiseInterface ...
     *
     * @return $this
     */
    public function andThen()
    {
        return call_user_func_array([$this, 'will'], func_get_args());
    }

    /**
     * @api
     *
     * @param mixed ...
     *
     * @return $this
     */
    public function andThenReturn()
    {
        return call_user_func_array([$this, 'willReturn'], func_get_args());
    }

    /**
     * @api
     *
     * @param string|\Exception ...
     *
     * @return $this
     */
    public function andThenThrow()
    {
        return call_user_func_array([$this, 'willThrow'], func_get_args());
    }

    /**
     * @internal
     *
     * {@inheritdoc}
     */
    public function execute(array $args, ObjectProphecy $object, MethodProphecy $method)
    {
        if (0 === count($this->promises)) {
            throw new \BadMethodCallException('No promises found.');
        }

        /** @var PromiseInterface $promise */
        $promise = array_shift($this->promises);

        if ($this->unexpectedCallsSupport && 0 === count($this->promises)) {
            $this->promises[] = $promise;
        }

        return $promise->execute($args, $object, $method);
    }

    /**
     * @throw \BadMethodCallException
     */
    private function assertThereIsNoUnexpectedCallsSupportYet()
    {
        if ($this->unexpectedCallsSupport) {
            throw new \BadMethodCallException('Cannot add another promise after the one defined for consecutive calls.');
        }
    }
}

<?php

namespace Pamil\ProphecyCommon\Example;

/**
 * @author Kamil Kokot <kamil@kokot.me>
 */
final class SubjectUnderSpecification
{
    /**
     * @var Collaborator
     */
    private $collaborator;

    /**
     * @param Collaborator $collaborator
     */
    public function __construct(Collaborator $collaborator)
    {
        $this->collaborator = $collaborator;
    }

    public function invokeCollabolator()
    {
        return $this->collaborator->invoke();
    }
}

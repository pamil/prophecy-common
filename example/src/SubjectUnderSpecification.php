<?php

namespace example\Pamil\ProphecyCommon;

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

    /**
     * @return mixed
     */
    public function invokeCollabolator()
    {
        return $this->collaborator->invoke();
    }
}

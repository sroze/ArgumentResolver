<?php

namespace ArgumentResolver\Argument;

class ArgumentDescriptions implements \IteratorAggregate
{
    /**
     * @var ArgumentDescription[]
     */
    private $descriptions;

    /**
     * @param ArgumentDescription[] $descriptions
     */
    public function __construct(array $descriptions = [])
    {
        $this->descriptions = $descriptions;
    }

    /**
     * @param ArgumentDescription $description
     */
    public function add(ArgumentDescription $description)
    {
        $this->descriptions[] = $description;
    }

    /**
     * Sort `ArgumentDescription`s by position.
     *
     * @return ArgumentDescriptions
     */
    public function sortByPosition()
    {
        usort($this->descriptions, function (ArgumentDescription $left, ArgumentDescription $right) {
            return $left->getPosition() > $right->getPosition() ? 1 : -1;
        });

        return $this;
    }

    /**
     * {@inheritdoc).
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->toArray());
    }

    /**
     * @return ArgumentDescription[]
     */
    public function toArray()
    {
        return $this->descriptions;
    }
}

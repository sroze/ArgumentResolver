<?php

namespace ArgumentResolver\Resolution;

class ResolutionConstraint
{
    const STRICT_MATCHING = 1;

    /**
     * @var int
     */
    private $type;

    /**
     * @var array
     */
    private $parameters;

    /**
     * @param int   $type
     * @param array $parameters
     */
    public function __construct($type, array $parameters = [])
    {
        $this->type = $type;
        $this->parameters = $parameters;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }
}

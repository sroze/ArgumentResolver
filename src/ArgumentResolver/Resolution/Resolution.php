<?php

namespace ArgumentResolver\Resolution;

class Resolution
{
    /**
     * @var int
     */
    private $position;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @var int
     */
    private $priority;

    /**
     * @param int   $position
     * @param mixed $value
     * @param int   $priority
     */
    public function __construct($position, $value, $priority)
    {
        $this->position = $position;
        $this->value = $value;
        $this->priority = $priority;
    }

    /**
     * @return mixed
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * @return int
     */
    public function position()
    {
        return $this->position;
    }

    /**
     * @return int
     */
    public function priority()
    {
        return $this->priority;
    }
}

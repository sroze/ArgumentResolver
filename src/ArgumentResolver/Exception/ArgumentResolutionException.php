<?php

namespace ArgumentResolver\Exception;

use ArgumentResolver\Argument\ArgumentDescription;

class ArgumentResolutionException extends ResolutionException
{
    /**
     * @var ArgumentDescription
     */
    private $argumentDescription;

    public function __construct($message, ArgumentDescription $argumentDescription, $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->argumentDescription = $argumentDescription;
    }

    /**
     * @return ArgumentDescription
     */
    public function getArgumentDescription()
    {
        return $this->argumentDescription;
    }
}

<?php
namespace SRIO\ArgumentResolver;

class ArgumentDescription 
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $position;

    /**
     * @var string
     */
    private $type;

    /**
     * @var bool
     */
    private $nullable;

    /**
     * Constructor.
     *
     * @param string $name
     * @param int    $position
     * @param string $type
     * @param bool   $nullable
     */
    public function __construct($name, $position, $type, $nullable)
    {
        $this->name = $name;
        $this->position = $position;
        $this->type = $type;
        $this->nullable = $nullable;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isNullable()
    {
        return $this->nullable;
    }
}

<?php
namespace SRIO\ArgumentResolver;

class ArgumentDescription
{
    const TYPE_ARRAY = 'array';
    const TYPE_SCALAR = 'scalar';

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
    private $required;

    /**
     * Constructor.
     *
     * @param string $name
     * @param int    $position
     * @param string $type
     * @param bool   $required
     */
    public function __construct($name, $position, $type, $required)
    {
        $this->name = $name;
        $this->position = $position;
        $this->type = $type;
        $this->required = $required;
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
    public function isRequired()
    {
        return $this->required;
    }

    /**
     * @return bool
     */
    public function isScalar()
    {
        return self::TYPE_SCALAR === $this->getType();
    }
}

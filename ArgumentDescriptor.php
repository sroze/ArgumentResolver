<?php
namespace SRIO\ArgumentResolver;

class ArgumentDescriptor 
{
    /**
     * Get argument descriptions of a callable.
     *
     * @param $callable
     * @return ArgumentDescription[]
     */
    public function getDescriptions($callable)
    {
        $reflection = $this->getReflection($callable);
        $descriptions = [];

        foreach($reflection->getParameters() as $parameter) {
            $descriptions[] = new ArgumentDescription(
                $parameter->name,
                $parameter->getPosition(),
                $this->getParameterType($parameter),
                $parameter->allowsNull()
            );
        }

        return $descriptions;
    }

    /**
     * @param \ReflectionParameter $parameter
     * @return string
     */
    private function getParameterType(\ReflectionParameter $parameter)
    {
        if (null !== ($class = $parameter->getClass())) {
            return $class->name;
        } else if ($parameter->isArray()) {
            return 'array';
        }

        return 'scalar';
    }

    /**
     * @param $value
     * @return string
     */
    public function getValueType($value)
    {
        if (is_object($value)) {
            return get_class($value);
        } else if (is_array($value)) {
            return 'array';
        }

        return 'scalar';
    }

    /**
     * @param $callable
     * @return \ReflectionFunctionAbstract
     */
    private function getReflection($callable)
    {
        if (!is_callable($callable)) {
            throw new \RuntimeException('Got a non-callable');
        } else if (is_array($callable)) {
            $reflectionClass = new \ReflectionClass($callable[0]);
            return $reflectionClass->getMethod($callable[1]);
        }

        return new \ReflectionFunction($callable);
    }
}

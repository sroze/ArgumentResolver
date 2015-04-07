<?php
namespace SRIO\ArgumentResolver;

use Doctrine\Common\Util\ClassUtils;

final class ArgumentDescriptor
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

        foreach ($reflection->getParameters() as $parameter) {
            $descriptions[] = new ArgumentDescription(
                $parameter->name,
                $parameter->getPosition(),
                $this->getParameterType($parameter),
                !$parameter->isOptional()
            );
        }

        return $descriptions;
    }

    /**
     * @param $value
     * @return string
     */
    public function getValueType($value)
    {
        if (is_object($value)) {
            return $this->getObjectClass($value);
        } elseif (is_array($value)) {
            return ArgumentDescription::TYPE_ARRAY;
        }

        return ArgumentDescription::TYPE_SCALAR;
    }

    /**
     * @param  \ReflectionParameter $parameter
     * @return string
     */
    private function getParameterType(\ReflectionParameter $parameter)
    {
        if (null !== ($class = $parameter->getClass())) {
            return $class->name;
        } elseif ($parameter->isArray()) {
            return ArgumentDescription::TYPE_ARRAY;
        }

        return ArgumentDescription::TYPE_SCALAR;
    }

    /**
     * @param $callable
     * @return \ReflectionFunctionAbstract
     */
    private function getReflection($callable)
    {
        if (!is_callable($callable)) {
            throw new \RuntimeException('Got a non-callable');
        } elseif (is_array($callable)) {
            $reflectionClass = new \ReflectionClass($callable[0]);

            return $reflectionClass->getMethod($callable[1]);
        }

        return new \ReflectionFunction($callable);
    }
    
    /**
     * Get object class.
     * 
     * It uses the Doctrine's `ClassUtils::getClass` method if exists, else the native `get_class` function.
     * 
     * @param object $object
     * @return string
     */
    private function getObjectClass($object)
    {
        return class_exists('Doctrine\Common\Util\ClassUtils') ? ClassUtils::getClass($object) : get_class($object);
    }
}

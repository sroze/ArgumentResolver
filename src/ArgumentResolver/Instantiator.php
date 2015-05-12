<?php

namespace ArgumentResolver;

use ArgumentResolver\Exception\ResolutionException;

class Instantiator
{
    /**
     * @var ArgumentResolver
     */
    private $argumentResolver;

    /**
     * @param ArgumentResolver $argumentResolver
     */
    public function __construct(ArgumentResolver $argumentResolver)
    {
        $this->argumentResolver = $argumentResolver;
    }

    /**
     * @param string $class
     * @param array  $availableArguments
     *
     * @throws ResolutionException
     *
     * @return object
     */
    public function instantiate($class, array $availableArguments)
    {
        $reflectionClass = new \ReflectionClass($class);
        if (null === ($constructor = $reflectionClass->getConstructor())) {
            throw new ResolutionException(sprintf(
                'Class "%s" do not have a constructor',
                $class
            ));
        }

        $arguments = $this->argumentResolver->resolveArguments($constructor, $availableArguments);

        return $reflectionClass->newInstanceArgs($arguments);
    }
}

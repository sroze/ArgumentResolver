<?php

namespace ArgumentResolver;

use ArgumentResolver\Argument\ArgumentDescriptor;
use ArgumentResolver\Resolution\ConstraintResolver;

final class ArgumentResolverFactory
{
    /**
     * Create an instance of ArgumentResolver.
     *
     * @return ArgumentResolver
     */
    public static function create()
    {
        return new ArgumentResolver(new ArgumentDescriptor(), new ConstraintResolver());
    }
}

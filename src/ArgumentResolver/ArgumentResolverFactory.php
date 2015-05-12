<?php

namespace ArgumentResolver;

final class ArgumentResolverFactory
{
    /**
     * Create an instance of ArgumentResolver.
     *
     * @return ArgumentResolver
     */
    public static function create()
    {
        return new ArgumentResolver(new ArgumentDescriptor());
    }
}

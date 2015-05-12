<?php
namespace SRIO\ArgumentResolver\Tests;

use ArgumentResolver\ArgumentResolver;
use ArgumentResolver\ArgumentResolverFactory;

class ArgumentResolverFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactoryReturnsAnArgumentResolver()
    {
        $resolver = ArgumentResolverFactory::create();
        $this->assertTrue($resolver instanceof ArgumentResolver);
    }
}

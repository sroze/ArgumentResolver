<?php
namespace SRIO\ArgumentResolver\Tests;

use SRIO\ArgumentResolver\ArgumentResolver;
use SRIO\ArgumentResolver\ArgumentResolverFactory;

class ArgumentResolverFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactoryReturnsAnArgumentResolver()
    {
        $resolver = ArgumentResolverFactory::create();
        $this->assertTrue($resolver instanceof ArgumentResolver);
    }
}

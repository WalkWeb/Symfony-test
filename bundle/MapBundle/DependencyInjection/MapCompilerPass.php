<?php

namespace MapBundle\DependencyInjection;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class MapCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        //$container->get('');
    }
}

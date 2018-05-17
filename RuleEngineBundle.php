<?php

namespace Cyve\RuleEngineBundle;

use Cyve\RuleEngineBundle\DependencyInjection\Compiler\RuleEnginePass;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RuleEngineBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new RuleEnginePass());
    }
}

<?php

namespace Cyve\RuleEngineBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class RuleEnginePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $ruleServices = $container->findTaggedServiceIds('app.rule');

        $injections = [];
        foreach ($ruleServices as $id => $tags) {
            list($engine, $priority) = $this->getRuleAttributes($tags);
            if(!$container->has($engine)) continue;

            $injections[$engine][] = ['id' => $id, 'priority' => $priority];
        }

        foreach($injections as $engine => $rules){
            $definition = $container->findDefinition($engine);
            foreach($this->sortRules($rules) as $rule){
                $definition->addMethodCall('addRule', array(new Reference($rule['id'])));
            }
        }
    }

    /**
     * @param array $tags
     * @return array
     */
    private function getRuleAttributes(array $tags): array
    {
        foreach($tags as $attributes){
            if(isset($attributes['engine'])){
                return [$attributes['engine'], $attributes['priority'] ?? 1];
            }
        }

        return [];
    }

    /**
     * @param array $rules
     * @return array
     */
    private function sortRules(array $rules): array
    {
        usort($rules, function($a, $b){
            return $a['priority'] <=> $b['priority'];
        });

        return $rules;
    }
}

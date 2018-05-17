<?php

namespace Cyve\RuleEngineBundle\Tests;

use Cyve\RuleEngineBundle\Rule\RuleInterface;
use Cyve\RuleEngineBundle\Engine\RuleEngine;
use PHPUnit\Framework\TestCase;

class RuleEngineTest extends TestCase
{
    /**
     * @dataProvider ruleEngineDataProvider
     */
    public function testRuleEngine($subject, $context, $expected)
    {
        $quantityRule = $this->createMock(RuleInterface::class);
        $quantityRule->method('supports')->willReturn(true);
        $quantityRule->method('handle')->will($this->returnCallback(function($subject, $context){
            return $subject * $context['quantity'];
        }));

        $promoRule = $this->createMock(RuleInterface::class);
        $promoRule->method('supports')->will($this->returnCallback(function($subject, $context){
            return $subject > 100;
        }));
        $promoRule->method('handle')->will($this->returnCallback(function($subject, $context){
            return $subject * (1 - $context['promo']);
        }));

        $deliveryRule = $this->createMock(RuleInterface::class);
        $deliveryRule->method('supports')->willReturn(true);
        $deliveryRule->method('handle')->will($this->returnCallback(function($subject, $context){
            return $subject + ($context['country'] === 'France' ? 5 : 10);
        }));

        $engine = (new RuleEngine())
            ->addRule($quantityRule)
            ->addRule($promoRule)
            ->addRule($deliveryRule)
        ;
        $result = $engine->handle($subject, $context);

        $this->assertEquals($expected, $result);
    }

    public function ruleEngineDataProvider()
    {
        yield [100, ['quantity' => 2, 'promo' => 0.1, 'country' => 'France'], 185];
        yield [100, ['quantity' => 2, 'promo' => 0.1, 'country' => 'Germany'], 190];
        yield [100, ['quantity' => 1, 'promo' => 0.1, 'country' => 'France'], 105];
    }
}

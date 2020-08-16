<?php

namespace Cyve\RuleEngine\Tests;

use Cyve\RuleEngine\Rule\RuleInterface;
use Cyve\RuleEngine\Engine\RuleEngine;
use PHPUnit\Framework\TestCase;

class RuleEngineTest extends TestCase
{
    public function test()
    {
        $context = [];
        $subject = (object) ['status' => null];

        $subjectAfterSupportedRule = (object) ['status' => 'supported'];
        $supportedRule = $this->createMock(RuleInterface::class);
        $supportedRule->expects($this->once())->method('supports')->with($subject, $context)->willReturn(true);
        $supportedRule->expects($this->once())->method('handle')->with($subject, $context)->willReturn($subjectAfterSupportedRule);

        $unsupportedRule = $this->createMock(RuleInterface::class);
        $unsupportedRule->expects($this->once())->method('supports')->with($subjectAfterSupportedRule, $context)->willReturn(false);
        $unsupportedRule->expects($this->never())->method('handle');

        $subjectAfterInvokableRule = (object) ['status' => 'invokable'];
        $invokableRule = $this->getMockBuilder(\stdClass::class)->addMethods(['__invoke'])->getMock();
        $invokableRule->expects($this->once())->method('__invoke')->with($subjectAfterSupportedRule, $context)->willReturn($subjectAfterInvokableRule);

        $subjectAfterCallableRule = (object) ['status' => 'callable'];
        $callableRule = function ($subject, $context) use ($subjectAfterCallableRule) {
            return $subjectAfterCallableRule;
        };

        $engine = new RuleEngine([$supportedRule, $unsupportedRule, $invokableRule, $callableRule]);
        $result = $engine->handle($subject, $context);

        $this->assertEquals($subjectAfterCallableRule, $result);
    }
}

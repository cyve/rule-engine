<?php

namespace Cyve\RuleEngineBundle\Rule;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class ExpressionRule implements RuleInterface
{
    /**
     * @var ExpressionLanguage
     */
    private $evaluator;

    /**
     * @var string
     */
    private $expression;

    public function __construct(string $expression)
    {
        $this->evaluator = new ExpressionLanguage();
        $this->expression = $expression;
    }

    /**
     * @param mixed $subject
     * @param mixed $context
     * @return bool
     */
    public function supports($subject, $context = null): bool
    {
        return true;
    }

    /**
     * @param mixed $subject
     * @param mixed $context
     * @return mixed
     */
    public function handle($subject, $context = null)
    {
        return $this->evaluator->evaluate($this->expression, ['subject' => $subject, 'context' => $context]);
    }
}

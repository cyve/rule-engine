<?php

namespace Cyve\RuleEngine\Rule;

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

    public function supports($subject, array $context = []): bool
    {
        return true;
    }

    public function handle($subject, array $context = [])
    {
        return $this->evaluator->evaluate($this->expression, ['subject' => $subject, 'context' => $context]);
    }
}

<?php

namespace Cyve\RuleEngine\Engine;

use Cyve\RuleEngine\Rule\RuleInterface;

class RuleEngine
{
    /**
     * @var iterable<RuleInterface>
     */
    private $rules;

    public function __construct(iterable $rules = [])
    {
        $this->rules = $rules;
    }

    public function handle($subject, array $context = [])
    {
        foreach($this->rules as $rule){
            if($rule->supports($subject, $context)) {
                $subject = $rule->handle($subject, $context);
            }
        }

        return $subject;
    }
}

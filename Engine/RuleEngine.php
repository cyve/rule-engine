<?php

namespace Cyve\RuleEngine\Engine;

use Cyve\RuleEngine\Rule\RuleInterface;

class RuleEngine
{
    /**
     * @var iterable
     */
    private $rules;

    public function __construct(iterable $rules = [])
    {
        $this->rules = $rules;
    }

    public function addRule(RuleInterface $rule)
    {
        @trigger_error(sprintf('Using method %s() is deprecated since version 1.1 and won\'t be supported anymore in 2.0.', __METHOD__), E_USER_DEPRECATED);

        $this->rules[] = $rule;

        return $this;
    }

    /**
     * @param mixed $subject
     * @return mixed
     */
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

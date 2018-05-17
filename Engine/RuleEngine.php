<?php

namespace Cyve\RuleEngineBundle\Engine;

use Cyve\RuleEngineBundle\Rule\RuleInterface;

class RuleEngine
{
    /**
     * @var array
     */
    private $rules = [];

    /**
     * @param RuleInterface $rule
     * @return $this
     */
    public function addRule(RuleInterface $rule)
    {
        $this->rules[] = $rule;

        return $this;
    }

    /**
     * @param mixed $subject
     * @param mixed $context
     * @return mixed
     */
    public function handle($subject, $context = null)
    {
        foreach($this->rules as $rule){
            if($rule->supports($subject, $context)) {
                $subject = $rule->handle($subject, $context);
            }
        }

        return $subject;
    }

}

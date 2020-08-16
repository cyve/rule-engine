<?php

namespace Cyve\RuleEngine\Engine;

use Cyve\RuleEngine\Rule\RuleInterface;

class RuleEngine
{
    /**
     * @var iterable<RuleInterface|callable>
     */
    private $rules;

    public function __construct(iterable $rules = [])
    {
        $this->rules = $rules;
    }

    public function handle($subject, array $context = [])
    {
        foreach($this->rules as $rule){
            if (is_callable($rule)) {
                $subject = call_user_func($rule, $subject, $context);
            } elseif ($rule instanceof RuleInterface) {
                if ($rule->supports($subject, $context)) {
                    $subject = $rule->handle($subject, $context);
                }
            } else {
                throw new \RuntimeException('Rule "%s" must be callable or implement interface "Cyve\\RuleEngine\\Rule\\RuleInterface".', get_class($rule));
            }
        }

        return $subject;
    }
}

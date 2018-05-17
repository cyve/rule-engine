<?php

namespace Cyve\RuleEngineBundle\Rule;

interface RuleInterface
{
    /**
     * @param mixed $subject
     * @param mixed $context
     * @return bool
     */
    public function supports($subject, $context = null): bool;

    /**
     * @param mixed $subject
     * @param mixed $context
     * @return mixed
     */
    public function handle($subject, $context = null);
}

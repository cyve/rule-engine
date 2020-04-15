<?php

namespace Cyve\RuleEngine\Rule;

interface RuleInterface
{
    public function supports($subject, array $context = []): bool;
    public function handle($subject, array $context = []);
}

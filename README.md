# Rule engine

## Installation:

With [Composer](http://packagist.org):
```sh
composer require cyve/rule-engine-bundle
```

## Usage

```php
use Cyve\RuleEngine\Engine\RuleEngine;
use Cyve\RuleEngine\Rule\ExpressionRule;

$engine = new RuleEngine([new ExpressionRule('subject * context["quantity"]')]);
$price = $engine->handle(100, ['quantity' => 2]); // 200
```

## Use case: price calculator (for Symfony)

```php
// src/Price/Rule/QuantityRule.php

namespace App\Price\Rule;

class QuantityRule implements \Cyve\RuleEngine\Rule\RuleInterface
{
    public function supports($subject, array $context = []): bool
    {
        return true;
    }

    public function handle($subject, array $context = [])
    {
        return $subject * ($context['quantity'] ?? 1);
    }
}
```
```php
// src/Price/Rule/PromoCodeRule.php

namespace App\Price\Rule;

class PromoCodeRule implements \Cyve\RuleEngine\Rule\RuleInterface
{
    public function supports($subject, array $context = []): bool
    {
        return isset($context['promoCode']);
    }

    public function handle($subject, array $context = [])
    {
        switch($context['promoCode']){
            case '10_PERCENT': return $subject * .9;
            default: return $subject;
        }
    }
}
```
```php
// src/Price/PriceCalculator.php

namespace App\Price;

class PriceCalculator extends \Cyve\RuleEngine\Engine\RuleEngine
{}
```
```yaml
# config/service.yml
# @see https://symfony.com/doc/4.4/service_container/tags.html#reference-tagged-services
App\Price\Rule\:
    resource: '../src/Price/Rule/*'
    tags: ['price.rule']
App\Price\PriceCalculator:
    public: true
    arguments:
        - !tagged_iterator price.rule
```
```php
// src/Controller/DefaultController.php
...
public function priceAction()
{
    $unitPrice = 100;

    $calculator = $this->get(PriceCalculator::class);
    $price = $calculator->handle($unitPrice, ['quantity' => 2, 'promoCode' => '10_PERCENT'])); // 180
}
...
```

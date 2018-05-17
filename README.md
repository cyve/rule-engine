# RuleEngineBundle

## Installation:

With [Composer](http://packagist.org):
```sh
composer require cyve/rule-engine-bundle
```

## Configuration

```php
// app/AppKernel.php
public function registerBundles()
{
    return array(
        // ...
        new Cyve\RuleEngineBundle\RuleEngineBundle(),
        // ...
    );
}
```

## Usage

```php
$engine = new RuleEngine();
$engine->addRule(new ExpressionRule('subject * context["quantity"]'));
$price = $engine->handle(100, ['quantity' => 2]); // 200
```

## Use case: price calculator

```php
// src/Price/Rule/QuantityRule.php

namespace App\Price\Rule;

class QuantityRule implements \Cyve\RuleEngineBundle\Rule\RuleInterface
{
    public function supports($subject, $context = null): bool
    {
        return true;
    }

    public function handle($subject, $context = null)
    {
        return $subject * ($context['quantity'] ?? 1);
    }
}
```
```php
// src/Price/Rule/PromoCodeRule.php

namespace App\Price\Rule;

class PromoCodeRule implements \Cyve\RuleEngineBundle\Rule\RuleInterface
{
    public function supports($subject, $context = null): bool
    {
        return isset($context['promoCode']);
    }

    public function handle($subject, $context = null)
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

class PriceCalculator extends \Cyve\RuleEngineBundle\Engine\RuleEngine
{}
```
```yaml
# app/config/service.yml
...
App\Price\PriceCalculator:
    public: true
App\Price\Rule\PromoCodeRule:
    tags: [{ name: 'app.rule', engine: 'App\Price\PriceCalculator', priority: 2 }]
App\Price\Rule\QuantityRule:
    tags: [{ name: 'app.rule', engine: 'App\Price\PriceCalculator', priority: 1 }]
...
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

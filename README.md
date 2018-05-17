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

# Installation
This package can be installed using composer:
```bash
composer require freezemage/benchmark
```

# Dependencies
`ext-intl`, `ext-mbstring` for output formatting.

# Usage
```php
use Freezemage\Benchmark\ResultSet;
use Freezemage\Benchmark\Runner;

$runner = new Runner();

$runner->iterations(5);

$find = static fn (array $parameters): bool => in_array(1, $parameters);

// With static parameters
$runner->parameters([1, 2, 3]);
$result = $runner->evaluate($find);

// With dynamic parameters (will be regenerated on each iteration)
$runner->dataProvider(static function (): array {
    $result = [];
    for ($i = 0; $i < 10; $i += 1) {
        $result[] = rand(0, 10);
    }
    return $result;
});
$result = $runner->evaluate($find);

// Output one
print $result->prettyPrint();

// Output multiple
$set = new ResultSet();
print $set->append('Run 1', $result)->prettyPrint(); // append each evaluate() result.
```

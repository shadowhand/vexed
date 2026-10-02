<div style="text-align:center;margin:0 auto;">
    <img src="docs/vexed-banner.jpg" style="width:100%;max-width:1200px" alt="Vexed Banner"/>
</div>

# Vexed

𑅃 API Problem ([RFC 9457][]) objects that can be used as `application/problem+json` documents.

[RFC 9457]: https://www.rfc-editor.org/rfc/rfc9457

## Installation

```sh
composer require vexed/vexed
```

## Usage

```php
use Vexed\Problem;

$problem = new Problem(
    type: 'https://example.com/faq/order-quantity-minimums',
    title: 'Minimum Quantity',
    detail: 'The minimum order quantity for this product is 100 units.',
    status: 400,
);

// They can be modified in place...
$problem->instance = '/cart/checkout';

// They can have extensions...
$problem->extend('refcode', 'MPQ.100');

// They can be converted to arrays...
$arr = $problem->toArray();

// They can be encoded directly to JSON...
$json = json_encode($problem);
```

Any of the standard RFC properties can be set:

- `type` a URI reference identifying the problem type
- `title` a short, human-readable summary of the problem type
- `detail` a human-readable explanation specific to this occurrence of the problem
- `status` the HTTP status code
- `instance` a URI reference that identifies the specific occurrence of the problem

_Note that these names are reserved and CANNOT be used as extension names. Attempting to do so will cause
a `ProblemException` to be thrown._

### HTTP Catalog

There are 40 HTTP problem classes available in `Vexed\Http\Client` and `Vexed\Http\Server`, such as:

- `Vexed\Http\Client\BadRequest`
- `Vexed\Http\Client\NotFound`
- `Vexed\Http\Server\InternalServerError`
- `Vexed\Http\Server\NotImplemented`
- ... and 36 other classes.

Each of the HTTP problems have an immutable `status` and `title` with the RFC 9110 reason phrase.

### Exception Transformer

The `Vexed\ExceptionTransformer` converts any `Throwable` into a `Problem`:

```php
use Throwable;
use Vexed\ExceptionTransformer;

$transformer = new ExceptionTransformer();

try {
    return $this->service->operation();
} catch (Throwable $e) {
    $problem = $transformer->transform($e);
}
```

By default, `transform()` will return an `InternalServerError`. This can be changed by defining a `map` that
associates a throwable class/interface with a factory that creates a problem. The transformer checks the throwable
class first, then its parent classes from the most immediate upward, then its interfaces. The first match wins.
When nothing matches, `InternalServerError` is returned:

```php
use Vexed\ExceptionTransformer;
use Vexed\Http\Client\BadRequest;
use Vexed\Http\Client\NotFound;

$transformer = new ExceptionTransformer(
    map: [
        NotFoundException::class => fn() => new NotFound(),
        ValidationException::class => fn(ValidationException $e) => new BadRequest()->extend('errors', $e->getErrors()),
    ],
    classExtension: 'exception',
    messageExtension: 'message',
);

try {
    $order = $this->orders->find($id);
    $order->prepare();
} catch (Throwable $e) {
    $problem = $transformer->transform($e);
}
```

The transformer can also add some extensions to the problem:

- When `classExtension` is defined, it will add `$throwable::class` as an extension.
- When `messageExtension` is defined, it will add `$throwable->getMessage()` as an extension.

Both are entirely optional and disabled by default.

```php
use Throwable;

$transformer = new ExceptionTransformer(
    classExtension: 'exception',
    messageExtension: 'message',
);

try {
    return $this->orders->process($order);
} catch (Throwable $e) {
    // The problem has been extended with `{exception: string, message: string}`
    $problem = $transformer->transform($e);
}
```

## Development

This project uses [Mago](https://mago.carthage.software/) for lint, formatting, and static analysis.

```
composer run fix       # automatically fix lint, analysis, and formatting issues
composer run format    # format source code
composer run check     # check style
composer run lint      # lint code
composer run analyze   # static analysis
composer run test      # unit testing with 100% coverage enforced
composer run verify    # run all verifications (check + lint + analyze + test)
```

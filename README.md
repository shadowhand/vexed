# Vexed

𑅃 Variable API Problem ([RFC 9457][]) data structure.

Provides objects for common issues as `application/problem+json` documents.
And turns any `Throwable` into a `Problem`.
And allows defining completely custom `Problem` types.

[RFC 9457]: https://www.rfc-editor.org/rfc/rfc9457

## Installation

```sh
composer require vexed/vexed
```

## Usage

```php
use Vexed\Http\NotFound;

// Create a problem instance
$problem = new NotFound();

// Convert the problem into a PSR-7 response using PSR-17 factories
$response = $problem->asResponse($responseFactory, $streamFactory);

// Or just convert it to JSON
$json = $problem->asJson();

// Or customize some properties
$problem = $problem->withDetail('This page is only available to authenticated users.');
```

### Custom

The `Problem` class is generic and can be used by itself:

```php
use Vexed\Problem;

$problem = new Problem(
    type: 'https://example.com/faq/order-quantity-minimums',
    title: 'Minimum Quantity',
    detail: 'The minimum order quantity for this product is 100 units.',
    status: 400,
);

// Now works just like HTTP problems
$json = $problem->asJson();
```

Any of the standard RFC properties can be set:

- `type` a URI reference identifying the problem type
- `title` a short, human-readable summary of the problem type
- `detail` a human-readable explanation specific to this occurrence of the problem
- `status` the HTTP status code
- `instance` a URI reference that identifies the specific occurrence of the problem

Additional extensions can be added using `withExtension($name, $value)`:

```php
$problem = $problem->withExtension('refcode', 'MPQ.100');
```

### Throwables

```php
use Vexed\Error;

// Convert any `Throwable` into a problem instance
$problem = new Error($throwable);

// Now works like any other problem
$json = $problem->asJson();
```

By default, all `Error` instances will have values like:

```json
{
    "type": "about:blank",
    "status": 500,
    "title": "Server Error"
}
```

_RFC 9457 §4.2.1 recommends that `title` be the HTTP status phrase when `type` is `about:blank`._

The exception class (without namespace) can be added to the problem `details` by setting `named: true`:

```php
$problem = new Error($throwable, named: true); // {"detail": "RuntimeException"}
```

Or, if you prefer to have the exception name in an extension property:

```php
$problem = new Error($throwable, named: 'class'); // {"class": "RuntimeException"}
```

Or, if you prefer to have the full exception name:

```
$problem = new Error($throwable, complete: true); // {"class": "Acme\\Order\\ChargeFailedException"}
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

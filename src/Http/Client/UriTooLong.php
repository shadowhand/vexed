<?php

declare(strict_types=1);

namespace Vexed\Http\Client;

use Vexed\Http\HttpProblem;
use Vexed\ProblemException;

/**
 * @api
 */
final class UriTooLong extends HttpProblem
{
    public ?string $title {
        get {
            return 'URI Too Long';
        }
        set {
            throw ProblemException::immutableTitle();
        }
    }

    public ?int $status {
        get {
            return 414;
        }
        set {
            throw ProblemException::immutableStatus();
        }
    }
}

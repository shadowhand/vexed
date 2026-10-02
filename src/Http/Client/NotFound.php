<?php

declare(strict_types=1);

namespace Vexed\Http\Client;

use Vexed\Http\HttpProblem;
use Vexed\ProblemException;

/**
 * @api
 */
final class NotFound extends HttpProblem
{
    public ?string $title {
        get {
            return 'Not Found';
        }
        set {
            throw ProblemException::immutableTitle();
        }
    }

    public ?int $status {
        get {
            return 404;
        }
        set {
            throw ProblemException::immutableStatus();
        }
    }
}

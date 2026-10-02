<?php

declare(strict_types=1);

namespace Vexed\Http\Client;

use Vexed\Http\HttpProblem;
use Vexed\ProblemException;

/**
 * @api
 */
final class PreconditionRequired extends HttpProblem
{
    public ?string $title {
        get {
            return 'Precondition Required';
        }
        set {
            throw ProblemException::immutableTitle();
        }
    }

    public ?int $status {
        get {
            return 428;
        }
        set {
            throw ProblemException::immutableStatus();
        }
    }
}

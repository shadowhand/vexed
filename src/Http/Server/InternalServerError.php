<?php

declare(strict_types=1);

namespace Vexed\Http\Server;

use Vexed\Http\HttpProblem;
use Vexed\ProblemException;

/**
 * @api
 */
final class InternalServerError extends HttpProblem
{
    public ?string $title {
        get {
            return 'Internal Server Error';
        }
        set {
            throw ProblemException::immutableTitle();
        }
    }

    public ?int $status {
        get {
            return 500;
        }
        set {
            throw ProblemException::immutableStatus();
        }
    }
}

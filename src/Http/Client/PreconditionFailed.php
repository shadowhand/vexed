<?php

declare(strict_types=1);

namespace Vexed\Http\Client;

use Vexed\Http\HttpProblem;
use Vexed\ProblemException;

/**
 * @api
 */
final class PreconditionFailed extends HttpProblem
{
    public ?string $title {
        get {
            return 'Precondition Failed';
        }
        set {
            throw ProblemException::immutableTitle();
        }
    }

    public ?int $status {
        get {
            return 412;
        }
        set {
            throw ProblemException::immutableStatus();
        }
    }
}

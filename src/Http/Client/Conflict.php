<?php

declare(strict_types=1);

namespace Vexed\Http\Client;

use Vexed\Http\HttpProblem;
use Vexed\ProblemException;

/**
 * @api
 */
final class Conflict extends HttpProblem
{
    public ?string $title {
        get {
            return 'Conflict';
        }
        set {
            throw ProblemException::immutableTitle();
        }
    }

    public ?int $status {
        get {
            return 409;
        }
        set {
            throw ProblemException::immutableStatus();
        }
    }
}

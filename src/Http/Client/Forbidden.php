<?php

declare(strict_types=1);

namespace Vexed\Http\Client;

use Vexed\Http\HttpProblem;
use Vexed\ProblemException;

/**
 * @api
 */
final class Forbidden extends HttpProblem
{
    public ?string $title {
        get {
            return 'Forbidden';
        }
        set {
            throw ProblemException::immutableTitle();
        }
    }

    public ?int $status {
        get {
            return 403;
        }
        set {
            throw ProblemException::immutableStatus();
        }
    }
}

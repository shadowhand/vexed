<?php

declare(strict_types=1);

namespace Vexed\Http\Client;

use Vexed\Http\HttpProblem;
use Vexed\ProblemException;

/**
 * @api
 */
final class MethodNotAllowed extends HttpProblem
{
    public ?string $title {
        get {
            return 'Method Not Allowed';
        }
        set {
            throw ProblemException::immutableTitle();
        }
    }

    public ?int $status {
        get {
            return 405;
        }
        set {
            throw ProblemException::immutableStatus();
        }
    }
}

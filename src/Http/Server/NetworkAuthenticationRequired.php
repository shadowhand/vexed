<?php

declare(strict_types=1);

namespace Vexed\Http\Server;

use Vexed\Http\HttpProblem;
use Vexed\ProblemException;

/**
 * @api
 */
final class NetworkAuthenticationRequired extends HttpProblem
{
    public ?string $title {
        get {
            return 'Network Authentication Required';
        }
        set {
            throw ProblemException::immutableTitle();
        }
    }

    public ?int $status {
        get {
            return 511;
        }
        set {
            throw ProblemException::immutableStatus();
        }
    }
}

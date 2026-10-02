<?php

declare(strict_types=1);

namespace Vexed\Http\Server;

use Vexed\Http\HttpProblem;
use Vexed\ProblemException;

/**
 * @api
 */
final class ServiceUnavailable extends HttpProblem
{
    public ?string $title {
        get {
            return 'Service Unavailable';
        }
        set {
            throw ProblemException::immutableTitle();
        }
    }

    public ?int $status {
        get {
            return 503;
        }
        set {
            throw ProblemException::immutableStatus();
        }
    }
}

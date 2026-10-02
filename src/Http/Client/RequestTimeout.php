<?php

declare(strict_types=1);

namespace Vexed\Http\Client;

use Vexed\Http\HttpProblem;
use Vexed\ProblemException;

/**
 * @api
 */
final class RequestTimeout extends HttpProblem
{
    public ?string $title {
        get {
            return 'Request Timeout';
        }
        set {
            throw ProblemException::immutableTitle();
        }
    }

    public ?int $status {
        get {
            return 408;
        }
        set {
            throw ProblemException::immutableStatus();
        }
    }
}

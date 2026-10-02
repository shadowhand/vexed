<?php

declare(strict_types=1);

namespace Vexed\Http\Client;

use Vexed\Http\HttpProblem;
use Vexed\ProblemException;

/**
 * @api
 */
final class TooManyRequests extends HttpProblem
{
    public ?string $title {
        get {
            return 'Too Many Requests';
        }
        set {
            throw ProblemException::immutableTitle();
        }
    }

    public ?int $status {
        get {
            return 429;
        }
        set {
            throw ProblemException::immutableStatus();
        }
    }
}

<?php

declare(strict_types=1);

namespace Vexed\Http\Client;

use Vexed\Http\HttpProblem;
use Vexed\ProblemException;

/**
 * @api
 */
final class TooEarly extends HttpProblem
{
    public ?string $title {
        get {
            return 'Too Early';
        }
        set {
            throw ProblemException::immutableTitle();
        }
    }

    public ?int $status {
        get {
            return 425;
        }
        set {
            throw ProblemException::immutableStatus();
        }
    }
}

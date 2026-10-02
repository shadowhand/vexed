<?php

declare(strict_types=1);

namespace Vexed\Http\Client;

use Vexed\Http\HttpProblem;
use Vexed\ProblemException;

/**
 * @api
 */
final class NotAcceptable extends HttpProblem
{
    public ?string $title {
        get {
            return 'Not Acceptable';
        }
        set {
            throw ProblemException::immutableTitle();
        }
    }

    public ?int $status {
        get {
            return 406;
        }
        set {
            throw ProblemException::immutableStatus();
        }
    }
}

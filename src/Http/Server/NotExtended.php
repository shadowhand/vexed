<?php

declare(strict_types=1);

namespace Vexed\Http\Server;

use Vexed\Http\HttpProblem;
use Vexed\ProblemException;

/**
 * @api
 */
final class NotExtended extends HttpProblem
{
    public ?string $title {
        get {
            return 'Not Extended';
        }
        set {
            throw ProblemException::immutableTitle();
        }
    }

    public ?int $status {
        get {
            return 510;
        }
        set {
            throw ProblemException::immutableStatus();
        }
    }
}

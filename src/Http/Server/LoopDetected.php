<?php

declare(strict_types=1);

namespace Vexed\Http\Server;

use Vexed\Http\HttpProblem;
use Vexed\ProblemException;

/**
 * @api
 */
final class LoopDetected extends HttpProblem
{
    public ?string $title {
        get {
            return 'Loop Detected';
        }
        set {
            throw ProblemException::immutableTitle();
        }
    }

    public ?int $status {
        get {
            return 508;
        }
        set {
            throw ProblemException::immutableStatus();
        }
    }
}

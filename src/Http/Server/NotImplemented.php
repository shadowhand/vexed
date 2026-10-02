<?php

declare(strict_types=1);

namespace Vexed\Http\Server;

use Vexed\Http\HttpProblem;
use Vexed\ProblemException;

/**
 * @api
 */
final class NotImplemented extends HttpProblem
{
    public ?string $title {
        get {
            return 'Not Implemented';
        }
        set {
            throw ProblemException::immutableTitle();
        }
    }

    public ?int $status {
        get {
            return 501;
        }
        set {
            throw ProblemException::immutableStatus();
        }
    }
}

<?php

declare(strict_types=1);

namespace Vexed\Http\Client;

use Vexed\Http\HttpProblem;
use Vexed\ProblemException;

/**
 * @api
 */
final class RangeNotSatisfiable extends HttpProblem
{
    public ?string $title {
        get {
            return 'Range Not Satisfiable';
        }
        set {
            throw ProblemException::immutableTitle();
        }
    }

    public ?int $status {
        get {
            return 416;
        }
        set {
            throw ProblemException::immutableStatus();
        }
    }
}

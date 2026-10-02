<?php

declare(strict_types=1);

namespace Vexed\Http\Client;

use Vexed\Http\HttpProblem;
use Vexed\ProblemException;

/**
 * @api
 */
final class Locked extends HttpProblem
{
    public ?string $title {
        get {
            return 'Locked';
        }
        set {
            throw ProblemException::immutableTitle();
        }
    }

    public ?int $status {
        get {
            return 423;
        }
        set {
            throw ProblemException::immutableStatus();
        }
    }
}

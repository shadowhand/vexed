<?php

declare(strict_types=1);

namespace Vexed\Http\Client;

use Vexed\Http\HttpProblem;
use Vexed\ProblemException;

/**
 * @api
 */
final class UpgradeRequired extends HttpProblem
{
    public ?string $title {
        get {
            return 'Upgrade Required';
        }
        set {
            throw ProblemException::immutableTitle();
        }
    }

    public ?int $status {
        get {
            return 426;
        }
        set {
            throw ProblemException::immutableStatus();
        }
    }
}

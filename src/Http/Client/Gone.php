<?php

declare(strict_types=1);

namespace Vexed\Http\Client;

use Vexed\Http\HttpProblem;
use Vexed\ProblemException;

/**
 * @api
 */
final class Gone extends HttpProblem
{
    public ?string $title {
        get {
            return 'Gone';
        }
        set {
            throw ProblemException::immutableTitle();
        }
    }

    public ?int $status {
        get {
            return 410;
        }
        set {
            throw ProblemException::immutableStatus();
        }
    }
}

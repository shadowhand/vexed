<?php

declare(strict_types=1);

namespace Vexed\Http\Client;

use Vexed\Http\HttpProblem;
use Vexed\ProblemException;

/**
 * @api
 */
final class UnavailableForLegalReasons extends HttpProblem
{
    public ?string $title {
        get {
            return 'Unavailable For Legal Reasons';
        }
        set {
            throw ProblemException::immutableTitle();
        }
    }

    public ?int $status {
        get {
            return 451;
        }
        set {
            throw ProblemException::immutableStatus();
        }
    }
}

<?php

declare(strict_types=1);

namespace Vexed\Http\Client;

use Vexed\Http\HttpProblem;
use Vexed\ProblemException;

/**
 * @api
 */
final class LengthRequired extends HttpProblem
{
    public ?string $title {
        get {
            return 'Length Required';
        }
        set {
            throw ProblemException::immutableTitle();
        }
    }

    public ?int $status {
        get {
            return 411;
        }
        set {
            throw ProblemException::immutableStatus();
        }
    }
}

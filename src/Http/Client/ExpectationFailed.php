<?php

declare(strict_types=1);

namespace Vexed\Http\Client;

use Vexed\Http\HttpProblem;
use Vexed\ProblemException;

/**
 * @api
 */
final class ExpectationFailed extends HttpProblem
{
    public ?string $title {
        get {
            return 'Expectation Failed';
        }
        set {
            throw ProblemException::immutableTitle();
        }
    }

    public ?int $status {
        get {
            return 417;
        }
        set {
            throw ProblemException::immutableStatus();
        }
    }
}

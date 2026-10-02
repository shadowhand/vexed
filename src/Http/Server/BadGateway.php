<?php

declare(strict_types=1);

namespace Vexed\Http\Server;

use Vexed\Http\HttpProblem;
use Vexed\ProblemException;

/**
 * @api
 */
final class BadGateway extends HttpProblem
{
    public ?string $title {
        get {
            return 'Bad Gateway';
        }
        set {
            throw ProblemException::immutableTitle();
        }
    }

    public ?int $status {
        get {
            return 502;
        }
        set {
            throw ProblemException::immutableStatus();
        }
    }
}

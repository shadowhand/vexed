<?php

declare(strict_types=1);

namespace Vexed\Http\Server;

use Vexed\Http\HttpProblem;
use Vexed\ProblemException;

/**
 * @api
 */
final class GatewayTimeout extends HttpProblem
{
    public ?string $title {
        get {
            return 'Gateway Timeout';
        }
        set {
            throw ProblemException::immutableTitle();
        }
    }

    public ?int $status {
        get {
            return 504;
        }
        set {
            throw ProblemException::immutableStatus();
        }
    }
}

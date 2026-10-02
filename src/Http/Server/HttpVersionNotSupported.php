<?php

declare(strict_types=1);

namespace Vexed\Http\Server;

use Vexed\Http\HttpProblem;
use Vexed\ProblemException;

/**
 * @api
 */
final class HttpVersionNotSupported extends HttpProblem
{
    public ?string $title {
        get {
            return 'HTTP Version Not Supported';
        }
        set {
            throw ProblemException::immutableTitle();
        }
    }

    public ?int $status {
        get {
            return 505;
        }
        set {
            throw ProblemException::immutableStatus();
        }
    }
}

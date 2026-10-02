<?php

declare(strict_types=1);

namespace Vexed\Http\Client;

use Vexed\Http\HttpProblem;
use Vexed\ProblemException;

/**
 * @api
 */
final class RequestHeaderFieldsTooLarge extends HttpProblem
{
    public ?string $title {
        get {
            return 'Request Header Fields Too Large';
        }
        set {
            throw ProblemException::immutableTitle();
        }
    }

    public ?int $status {
        get {
            return 431;
        }
        set {
            throw ProblemException::immutableStatus();
        }
    }
}

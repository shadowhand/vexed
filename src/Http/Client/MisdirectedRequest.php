<?php

declare(strict_types=1);

namespace Vexed\Http\Client;

use Vexed\Http\HttpProblem;
use Vexed\ProblemException;

/**
 * @api
 */
final class MisdirectedRequest extends HttpProblem
{
    public ?string $title {
        get {
            return 'Misdirected Request';
        }
        set {
            throw ProblemException::immutableTitle();
        }
    }

    public ?int $status {
        get {
            return 421;
        }
        set {
            throw ProblemException::immutableStatus();
        }
    }
}

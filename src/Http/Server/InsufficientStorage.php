<?php

declare(strict_types=1);

namespace Vexed\Http\Server;

use Vexed\Http\HttpProblem;
use Vexed\ProblemException;

/**
 * @api
 */
final class InsufficientStorage extends HttpProblem
{
    public ?string $title {
        get {
            return 'Insufficient Storage';
        }
        set {
            throw ProblemException::immutableTitle();
        }
    }

    public ?int $status {
        get {
            return 507;
        }
        set {
            throw ProblemException::immutableStatus();
        }
    }
}

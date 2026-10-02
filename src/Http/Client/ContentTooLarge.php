<?php

declare(strict_types=1);

namespace Vexed\Http\Client;

use Vexed\Http\HttpProblem;
use Vexed\ProblemException;

/**
 * @api
 */
final class ContentTooLarge extends HttpProblem
{
    public ?string $title {
        get {
            return 'Content Too Large';
        }
        set {
            throw ProblemException::immutableTitle();
        }
    }

    public ?int $status {
        get {
            return 413;
        }
        set {
            throw ProblemException::immutableStatus();
        }
    }
}

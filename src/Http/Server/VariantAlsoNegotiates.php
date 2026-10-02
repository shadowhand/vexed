<?php

declare(strict_types=1);

namespace Vexed\Http\Server;

use Vexed\Http\HttpProblem;
use Vexed\ProblemException;

/**
 * @api
 */
final class VariantAlsoNegotiates extends HttpProblem
{
    public ?string $title {
        get {
            return 'Variant Also Negotiates';
        }
        set {
            throw ProblemException::immutableTitle();
        }
    }

    public ?int $status {
        get {
            return 506;
        }
        set {
            throw ProblemException::immutableStatus();
        }
    }
}

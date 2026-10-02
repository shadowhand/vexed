<?php

declare(strict_types=1);

namespace Vexed\Http\Client;

use Vexed\Http\HttpProblem;
use Vexed\ProblemException;

/**
 * @api
 */
final class BadRequest extends HttpProblem
{
    public ?string $title {
        get {
            return 'Bad Request';
        }
        set {
            throw ProblemException::immutableTitle();
        }
    }

    public ?int $status {
        get {
            return 400;
        }
        set {
            throw ProblemException::immutableStatus();
        }
    }
}

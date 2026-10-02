<?php

declare(strict_types=1);

namespace Vexed\Http\Client;

use Vexed\Http\HttpProblem;
use Vexed\ProblemException;

/**
 * @api
 */
final class ImATeapot extends HttpProblem
{
    public ?string $title {
        get {
            return 'I\'m a Teapot';
        }
        set {
            throw ProblemException::immutableTitle();
        }
    }

    public ?int $status {
        get {
            return 418;
        }
        set {
            throw ProblemException::immutableStatus();
        }
    }
}

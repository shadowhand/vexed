<?php

declare(strict_types=1);

namespace Vexed\Http\Client;

use Vexed\Http\HttpProblem;
use Vexed\ProblemException;

/**
 * @api
 */
final class PaymentRequired extends HttpProblem
{
    public ?string $title {
        get {
            return 'Payment Required';
        }
        set {
            throw ProblemException::immutableTitle();
        }
    }

    public ?int $status {
        get {
            return 402;
        }
        set {
            throw ProblemException::immutableStatus();
        }
    }
}

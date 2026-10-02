<?php

declare(strict_types=1);

namespace Vexed\Http;

use Vexed\Problem;

/**
 * @api
 */
abstract class HttpProblem extends Problem
{
    final public function __construct(?string $type = null, ?string $detail = null, ?string $instance = null)
    {
        $this->type = $type;
        $this->detail = $detail;
        $this->instance = $instance;
    }
}

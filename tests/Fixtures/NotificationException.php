<?php

declare(strict_types=1);

namespace Vexed\Tests\Fixtures;

use RuntimeException;

/**
 * @internal
 */
final class NotificationException extends RuntimeException implements Recoverable {}

<?php

declare(strict_types=1);

namespace Vexed;

use Closure;
use Throwable;
use Vexed\Http\Server\InternalServerError;

use function class_implements;
use function class_parents;

final readonly class ExceptionTransformer
{
    public function __construct(
        /** @var array<class-string<Throwable>, Closure(Throwable):Problem> */
        private array $map = [],
        private ?string $classExtension = null,
        private ?string $messageExtension = null,
    ) {}

    public function transform(Throwable $throwable): Problem
    {
        $problem = $this->determineProblem($throwable);

        if ($this->classExtension) {
            $problem->extend($this->classExtension, $throwable::class);
        }

        if ($this->messageExtension) {
            $problem->extend($this->messageExtension, $throwable->getMessage());
        }

        return $problem;
    }

    private function determineProblem(Throwable $throwable): Problem
    {
        $classes = [
            $throwable::class,
            // @mago-expect analysis:invalid-array-element
            ...class_parents($throwable),
            // @mago-expect analysis:invalid-array-element
            ...class_implements($throwable),
        ];

        // @mago-expect analysis:mixed-assignment
        foreach ($classes as $class) {
            $factory = $this->map[$class] ?? null;

            if ($factory instanceof Closure) {
                return $factory($throwable);
            }
        }

        return new InternalServerError();
    }
}

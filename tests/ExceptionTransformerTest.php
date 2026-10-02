<?php

declare(strict_types=1);

namespace Vexed\Tests;

use DomainException;
use Exception;
use LogicException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Throwable;
use Vexed\ExceptionTransformer;
use Vexed\Http\Client\BadRequest;
use Vexed\Http\Client\Gone;
use Vexed\Http\Client\NotFound;
use Vexed\Http\Server\InternalServerError;
use Vexed\Problem;
use Vexed\Tests\Fixtures\NotificationException;
use Vexed\Tests\Fixtures\Recoverable;

#[CoversClass(ExceptionTransformer::class)]
final class ExceptionTransformerTest extends TestCase
{
    public function testWithoutAMapItProducesAnInternalServerError(): void
    {
        $problem = new ExceptionTransformer()->transform(new RuntimeException('boom'));

        $this->assertInstanceOf(InternalServerError::class, $problem);
        $this->assertSame('Internal Server Error', $problem->title);
        $this->assertSame(500, $problem->status);
        $this->assertSame([], $problem->extensions);
        $this->assertSame(['title' => 'Internal Server Error', 'status' => 500], $problem->toArray());
    }

    public function testAnUnmappedThrowableFallsBackToAnInternalServerError(): void
    {
        $transformer = new ExceptionTransformer([
            NotificationException::class => static fn(Throwable $_throwable): Problem => new BadRequest(),
        ]);

        $problem = $transformer->transform(new RuntimeException('boom'));

        $this->assertInstanceOf(InternalServerError::class, $problem);
    }

    public function testUsesTheFactoryRegisteredForTheExactClass(): void
    {
        $transformer = new ExceptionTransformer([
            NotificationException::class => static fn(Throwable $throwable): Problem => new NotFound(
                detail: $throwable->getMessage(),
            ),
        ]);

        $problem = $transformer->transform(new NotificationException('notification not found'));

        $this->assertInstanceOf(NotFound::class, $problem);
        $this->assertSame('notification not found', $problem->detail);
        $this->assertSame(404, $problem->status);
    }

    public function testUsesTheFactoryRegisteredForAParentClass(): void
    {
        $transformer = new ExceptionTransformer([
            RuntimeException::class => static fn(Throwable $_throwable): Problem => new BadRequest(),
        ]);

        $problem = $transformer->transform(new NotificationException('invalid'));

        $this->assertInstanceOf(BadRequest::class, $problem);
    }

    public function testUsesTheFactoryRegisteredForAnInterface(): void
    {
        $transformer = new ExceptionTransformer([
            Recoverable::class => static fn(Throwable $_throwable): Problem => new Gone(),
        ]);

        $problem = $transformer->transform(new NotificationException('gone'));

        $this->assertInstanceOf(Gone::class, $problem);
    }

    public function testTheExactClassTakesPrecedenceOverAParentAndAnInterface(): void
    {
        $transformer = new ExceptionTransformer([
            NotificationException::class => static fn(Throwable $_throwable): Problem => new NotFound(),
            RuntimeException::class => static fn(Throwable $_throwable): Problem => new BadRequest(),
            Recoverable::class => static fn(Throwable $_throwable): Problem => new Gone(),
        ]);

        $problem = $transformer->transform(new NotificationException('specific'));

        $this->assertInstanceOf(NotFound::class, $problem);
    }

    public function testTheNearestParentTakesPrecedence(): void
    {
        $transformer = new ExceptionTransformer([
            Exception::class => static fn(Throwable $_throwable): Problem => new BadRequest(),
            LogicException::class => static fn(Throwable $_throwable): Problem => new NotFound(),
        ]);

        $problem = $transformer->transform(new DomainException('near'));

        $this->assertInstanceOf(NotFound::class, $problem);
    }

    public function testAParentTakesPrecedenceOverAnInterface(): void
    {
        $transformer = new ExceptionTransformer([
            Throwable::class => static fn(Throwable $_throwable): Problem => new Gone(),
            RuntimeException::class => static fn(Throwable $_throwable): Problem => new NotFound(),
        ]);

        $problem = $transformer->transform(new NotificationException('parent'));

        $this->assertInstanceOf(NotFound::class, $problem);
    }

    public function testReturnsTheProblemBuiltByTheFactory(): void
    {
        $factoryProblem = new NotFound(detail: 'built by the factory');

        $transformer = new ExceptionTransformer([
            NotificationException::class => static fn(Throwable $_throwable): Problem => $factoryProblem,
        ]);

        $this->assertSame($factoryProblem, $transformer->transform(new NotificationException('boom')));
    }

    public function testTheFactoryReceivesTheOriginalThrowable(): void
    {
        $captured = null;

        $transformer = new ExceptionTransformer([
            NotificationException::class => static function (Throwable $throwable) use (&$captured): Problem {
                $captured = $throwable;

                return new NotFound();
            },
        ]);

        $exception = new NotificationException('boom');
        $transformer->transform($exception);

        $this->assertSame($exception, $captured);
    }

    public function testTheClassExtensionRecordsTheThrowableClass(): void
    {
        $problem = new ExceptionTransformer(classExtension: 'exception')->transform(new NotificationException('boom'));

        $this->assertSame(['exception' => NotificationException::class], $problem->extensions);
        $this->assertSame(
            [
                'title' => 'Internal Server Error',
                'status' => 500,
                'exception' => NotificationException::class,
            ],
            $problem->toArray(),
        );
    }

    public function testTheMessageExtensionRecordsTheThrowableMessage(): void
    {
        $problem = new ExceptionTransformer(messageExtension: 'message')->transform(
            new NotificationException('something broke'),
        );

        $this->assertSame(['message' => 'something broke'], $problem->extensions);
    }

    public function testBothExtensionsAreAppliedWithTheClassFirst(): void
    {
        $problem = new ExceptionTransformer(classExtension: 'exception', messageExtension: 'message')->transform(
            new NotificationException('something broke'),
        );

        $this->assertSame(
            ['exception' => NotificationException::class, 'message' => 'something broke'],
            $problem->extensions,
        );
    }

    public function testExtensionsAreAppliedToTheFactoryProblem(): void
    {
        $transformer = new ExceptionTransformer(
            map: [
                NotificationException::class => static fn(Throwable $_throwable): Problem => new NotFound(),
            ],
            classExtension: 'exception',
            messageExtension: 'message',
        );

        $problem = $transformer->transform(new NotificationException('boom'));

        $this->assertInstanceOf(NotFound::class, $problem);
        $this->assertSame(['exception' => NotificationException::class, 'message' => 'boom'], $problem->extensions);
    }
}

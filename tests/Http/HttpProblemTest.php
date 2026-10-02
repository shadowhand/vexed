<?php

declare(strict_types=1);

namespace Vexed\Tests\Http;

use Closure;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Vexed\Http\Client\BadRequest;
use Vexed\Http\Client\Conflict;
use Vexed\Http\Client\ContentTooLarge;
use Vexed\Http\Client\ExpectationFailed;
use Vexed\Http\Client\FailedDependency;
use Vexed\Http\Client\Forbidden;
use Vexed\Http\Client\Gone;
use Vexed\Http\Client\ImATeapot;
use Vexed\Http\Client\LengthRequired;
use Vexed\Http\Client\Locked;
use Vexed\Http\Client\MethodNotAllowed;
use Vexed\Http\Client\MisdirectedRequest;
use Vexed\Http\Client\NotAcceptable;
use Vexed\Http\Client\NotFound;
use Vexed\Http\Client\PaymentRequired;
use Vexed\Http\Client\PreconditionFailed;
use Vexed\Http\Client\PreconditionRequired;
use Vexed\Http\Client\ProxyAuthenticationRequired;
use Vexed\Http\Client\RangeNotSatisfiable;
use Vexed\Http\Client\RequestHeaderFieldsTooLarge;
use Vexed\Http\Client\RequestTimeout;
use Vexed\Http\Client\TooEarly;
use Vexed\Http\Client\TooManyRequests;
use Vexed\Http\Client\Unauthorized;
use Vexed\Http\Client\UnavailableForLegalReasons;
use Vexed\Http\Client\UnprocessableContent;
use Vexed\Http\Client\UnsupportedMediaType;
use Vexed\Http\Client\UpgradeRequired;
use Vexed\Http\Client\UriTooLong;
use Vexed\Http\HttpProblem;
use Vexed\Http\Server\BadGateway;
use Vexed\Http\Server\GatewayTimeout;
use Vexed\Http\Server\HttpVersionNotSupported;
use Vexed\Http\Server\InsufficientStorage;
use Vexed\Http\Server\InternalServerError;
use Vexed\Http\Server\LoopDetected;
use Vexed\Http\Server\NetworkAuthenticationRequired;
use Vexed\Http\Server\NotExtended;
use Vexed\Http\Server\NotImplemented;
use Vexed\Http\Server\ServiceUnavailable;
use Vexed\Http\Server\VariantAlsoNegotiates;
use Vexed\Problem;
use Vexed\ProblemException;

use function json_encode;

use const JSON_THROW_ON_ERROR;

#[CoversClass(HttpProblem::class)]
#[CoversClass(Problem::class)]
#[CoversClass(ProblemException::class)]
#[CoversClass(BadGateway::class)]
#[CoversClass(BadRequest::class)]
#[CoversClass(Conflict::class)]
#[CoversClass(ContentTooLarge::class)]
#[CoversClass(ExpectationFailed::class)]
#[CoversClass(FailedDependency::class)]
#[CoversClass(Forbidden::class)]
#[CoversClass(GatewayTimeout::class)]
#[CoversClass(Gone::class)]
#[CoversClass(HttpVersionNotSupported::class)]
#[CoversClass(ImATeapot::class)]
#[CoversClass(InsufficientStorage::class)]
#[CoversClass(InternalServerError::class)]
#[CoversClass(LengthRequired::class)]
#[CoversClass(Locked::class)]
#[CoversClass(LoopDetected::class)]
#[CoversClass(MethodNotAllowed::class)]
#[CoversClass(MisdirectedRequest::class)]
#[CoversClass(NetworkAuthenticationRequired::class)]
#[CoversClass(NotAcceptable::class)]
#[CoversClass(NotExtended::class)]
#[CoversClass(NotFound::class)]
#[CoversClass(NotImplemented::class)]
#[CoversClass(PaymentRequired::class)]
#[CoversClass(PreconditionFailed::class)]
#[CoversClass(PreconditionRequired::class)]
#[CoversClass(ProxyAuthenticationRequired::class)]
#[CoversClass(RangeNotSatisfiable::class)]
#[CoversClass(RequestHeaderFieldsTooLarge::class)]
#[CoversClass(RequestTimeout::class)]
#[CoversClass(ServiceUnavailable::class)]
#[CoversClass(TooEarly::class)]
#[CoversClass(TooManyRequests::class)]
#[CoversClass(Unauthorized::class)]
#[CoversClass(UnavailableForLegalReasons::class)]
#[CoversClass(UnprocessableContent::class)]
#[CoversClass(UnsupportedMediaType::class)]
#[CoversClass(UpgradeRequired::class)]
#[CoversClass(UriTooLong::class)]
#[CoversClass(VariantAlsoNegotiates::class)]
final class HttpProblemTest extends TestCase
{
    /**
     * @param Closure(): HttpProblem $make
     */
    #[DataProvider('catalog')]
    public function testDefaultsForEachStatus(Closure $make, string $title, int $status): void
    {
        $problem = $make();

        $this->assertNull($problem->type);
        $this->assertSame($title, $problem->title);
        $this->assertNull($problem->detail);
        $this->assertSame($status, $problem->status);
        $this->assertNull($problem->instance);
        $this->assertSame([], $problem->extensions);
        $this->assertSame(['title' => $title, 'status' => $status], $problem->toArray());
    }

    /**
     * @param Closure(): HttpProblem $make
     */
    #[DataProvider('catalog')]
    public function testTitleAndStatusAreImmutable(Closure $make, string $title, int $status): void
    {
        $problem = $make();

        try {
            $problem->title = 'Changed';
            $this->fail('The title should be immutable.');
        } catch (ProblemException $exception) {
            $this->assertSame('Cannot overwrite HTTP title', $exception->getMessage());
        }

        try {
            $problem->status = 500;
            $this->fail('The status should be immutable.');
        } catch (ProblemException $exception) {
            $this->assertSame('Cannot overwrite HTTP status', $exception->getMessage());
        }

        $this->assertSame($title, $problem->title);
        $this->assertSame($status, $problem->status);
    }

    public function testConstructorArgumentsAreCarriedThrough(): void
    {
        $problem = new NotFound('https://example.com/problems/not-found', 'No such page.', '/pages/7');

        $this->assertSame('https://example.com/problems/not-found', $problem->type);
        $this->assertSame('Not Found', $problem->title);
        $this->assertSame('No such page.', $problem->detail);
        $this->assertSame(404, $problem->status);
        $this->assertSame('/pages/7', $problem->instance);
        $this->assertSame(
            [
                'type' => 'https://example.com/problems/not-found',
                'title' => 'Not Found',
                'detail' => 'No such page.',
                'status' => 404,
                'instance' => '/pages/7',
            ],
            $problem->toArray(),
        );
    }

    public function testMutableMembersCanBeChanged(): void
    {
        $problem = new NotFound();

        $problem->type = 'https://example.com/problems/not-found';
        $problem->detail = 'No such page.';
        $problem->instance = '/pages/7';
        $problem->extend('refcode', 'NF.7');

        $this->assertSame('https://example.com/problems/not-found', $problem->type);
        $this->assertSame('No such page.', $problem->detail);
        $this->assertSame('/pages/7', $problem->instance);
        $this->assertSame(
            [
                'type' => 'https://example.com/problems/not-found',
                'title' => 'Not Found',
                'detail' => 'No such page.',
                'status' => 404,
                'instance' => '/pages/7',
                'refcode' => 'NF.7',
            ],
            $problem->toArray(),
        );
    }

    public function testJsonEncodeProducesTheProblemDocument(): void
    {
        $this->assertSame('{"title":"Not Found","status":404}', json_encode(new NotFound(), JSON_THROW_ON_ERROR));
    }

    /**
     * @return array<string, array{Closure(): HttpProblem, string, int}>
     */
    public static function catalog(): array
    {
        return [
            'BadGateway' => [static fn(): HttpProblem => new BadGateway(), 'Bad Gateway', 502],
            'BadRequest' => [static fn(): HttpProblem => new BadRequest(), 'Bad Request', 400],
            'Conflict' => [static fn(): HttpProblem => new Conflict(), 'Conflict', 409],
            'ContentTooLarge' => [static fn(): HttpProblem => new ContentTooLarge(), 'Content Too Large', 413],
            'ExpectationFailed' => [static fn(): HttpProblem => new ExpectationFailed(), 'Expectation Failed', 417],
            'FailedDependency' => [static fn(): HttpProblem => new FailedDependency(), 'Failed Dependency', 424],
            'Forbidden' => [static fn(): HttpProblem => new Forbidden(), 'Forbidden', 403],
            'GatewayTimeout' => [static fn(): HttpProblem => new GatewayTimeout(), 'Gateway Timeout', 504],
            'Gone' => [static fn(): HttpProblem => new Gone(), 'Gone', 410],
            'HttpVersionNotSupported' => [
                static fn(): HttpProblem => new HttpVersionNotSupported(),
                'HTTP Version Not Supported',
                505,
            ],
            'ImATeapot' => [static fn(): HttpProblem => new ImATeapot(), 'I\'m a Teapot', 418],
            'InsufficientStorage' => [
                static fn(): HttpProblem => new InsufficientStorage(),
                'Insufficient Storage',
                507,
            ],
            'InternalServerError' => [
                static fn(): HttpProblem => new InternalServerError(),
                'Internal Server Error',
                500,
            ],
            'LengthRequired' => [static fn(): HttpProblem => new LengthRequired(), 'Length Required', 411],
            'Locked' => [static fn(): HttpProblem => new Locked(), 'Locked', 423],
            'LoopDetected' => [static fn(): HttpProblem => new LoopDetected(), 'Loop Detected', 508],
            'MethodNotAllowed' => [static fn(): HttpProblem => new MethodNotAllowed(), 'Method Not Allowed', 405],
            'MisdirectedRequest' => [static fn(): HttpProblem => new MisdirectedRequest(), 'Misdirected Request', 421],
            'NetworkAuthenticationRequired' => [
                static fn(): HttpProblem => new NetworkAuthenticationRequired(),
                'Network Authentication Required',
                511,
            ],
            'NotAcceptable' => [static fn(): HttpProblem => new NotAcceptable(), 'Not Acceptable', 406],
            'NotExtended' => [static fn(): HttpProblem => new NotExtended(), 'Not Extended', 510],
            'NotFound' => [static fn(): HttpProblem => new NotFound(), 'Not Found', 404],
            'NotImplemented' => [static fn(): HttpProblem => new NotImplemented(), 'Not Implemented', 501],
            'PaymentRequired' => [static fn(): HttpProblem => new PaymentRequired(), 'Payment Required', 402],
            'PreconditionFailed' => [static fn(): HttpProblem => new PreconditionFailed(), 'Precondition Failed', 412],
            'PreconditionRequired' => [
                static fn(): HttpProblem => new PreconditionRequired(),
                'Precondition Required',
                428,
            ],
            'ProxyAuthenticationRequired' => [
                static fn(): HttpProblem => new ProxyAuthenticationRequired(),
                'Proxy Authentication Required',
                407,
            ],
            'RangeNotSatisfiable' => [
                static fn(): HttpProblem => new RangeNotSatisfiable(),
                'Range Not Satisfiable',
                416,
            ],
            'RequestHeaderFieldsTooLarge' => [
                static fn(): HttpProblem => new RequestHeaderFieldsTooLarge(),
                'Request Header Fields Too Large',
                431,
            ],
            'RequestTimeout' => [static fn(): HttpProblem => new RequestTimeout(), 'Request Timeout', 408],
            'ServiceUnavailable' => [static fn(): HttpProblem => new ServiceUnavailable(), 'Service Unavailable', 503],
            'TooEarly' => [static fn(): HttpProblem => new TooEarly(), 'Too Early', 425],
            'TooManyRequests' => [static fn(): HttpProblem => new TooManyRequests(), 'Too Many Requests', 429],
            'Unauthorized' => [static fn(): HttpProblem => new Unauthorized(), 'Unauthorized', 401],
            'UnavailableForLegalReasons' => [
                static fn(): HttpProblem => new UnavailableForLegalReasons(),
                'Unavailable For Legal Reasons',
                451,
            ],
            'UnprocessableContent' => [
                static fn(): HttpProblem => new UnprocessableContent(),
                'Unprocessable Content',
                422,
            ],
            'UnsupportedMediaType' => [
                static fn(): HttpProblem => new UnsupportedMediaType(),
                'Unsupported Media Type',
                415,
            ],
            'UpgradeRequired' => [static fn(): HttpProblem => new UpgradeRequired(), 'Upgrade Required', 426],
            'UriTooLong' => [static fn(): HttpProblem => new UriTooLong(), 'URI Too Long', 414],
            'VariantAlsoNegotiates' => [
                static fn(): HttpProblem => new VariantAlsoNegotiates(),
                'Variant Also Negotiates',
                506,
            ],
        ];
    }
}

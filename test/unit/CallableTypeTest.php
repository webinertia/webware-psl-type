<?php

declare(strict_types=1);

namespace Psl\Type\Test;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Psl\Type\Exception\AssertException;
use Psl\Type\Exception\CoercionException;
use Psl\Type\Internal\CallableType;

use function Psl\Type\callable_type;
use function Psl\Type\int;
use function Psl\Type\optional;
use function Psl\Type\shape;
use function Psl\Type\string;
use function Psl\Type\union;

#[CoversClass(CallableType::class)]
#[CoversMethod(CallableType::class, 'assert')]
#[CoversMethod(CallableType::class, 'coerce')]
#[CoversMethod(CallableType::class, 'matches')]
#[CoversMethod(CallableType::class, 'toString')]
#[CoversFunction('Psl\Type\callable_type')]
final class CallableTypeTest extends TestCase
{
    #[Test]
    public function callableTypeAssertThrowsOnInvalidValue(): void
    {
        $this->expectException(AssertException::class);

        callable_type()->assert('this is not callable');
    }

    #[Test]
    public function callableTypeCoerceReturnsCallableValues(): void
    {
        static::assertSame('strlen', callable_type()->coerce('strlen'));
    }

    #[Test]
    public function callableTypeCoerceThrowsOnInvalidValue(): void
    {
        $this->expectException(CoercionException::class);

        callable_type()->coerce(42);
    }

    #[Test]
    public function callableTypeMatchesCallableValues(): void
    {
        $type = callable_type();

        static::assertTrue($type->matches('strlen'));
        static::assertTrue($type->matches(static fn(): null => null));
        static::assertFalse($type->matches('this is not callable'));
        static::assertFalse($type->matches(42));
    }

    #[Test]
    public function callableTypeToStringIsCallable(): void
    {
        static::assertSame('callable', callable_type()->toString());
    }

    #[Test]
    public function shapeRejectsListenerThatIsNeitherStringNorCallable(): void
    {
        $specShape = shape([
            'listener' => union(string(), callable_type()),
            'priority' => optional(int()),
        ]);

        $this->expectException(AssertException::class);

        $specShape->assert([
            'listener' => 42,
        ]);
    }

    #[Test]
    public function shapeWithUnionOfStringOrCallableListenerAndOptionalPriority(): void
    {
        $specShape = shape([
            'listener' => union(string(), callable_type()),
            'priority' => optional(int()),
        ]);

        $withStringListener = $specShape->assert([
            'listener' => 'SomeClass::someMethod',
            'priority' => 10,
        ]);

        static::assertSame('SomeClass::someMethod', $withStringListener['listener']);
        static::assertSame(10, $withStringListener['priority']);

        $listener             = static fn(): null => null;
        $withCallableListener = $specShape->assert([
            'listener' => $listener,
        ]);

        static::assertSame($listener, $withCallableListener['listener']);
        static::assertArrayNotHasKey('priority', $withCallableListener);
    }
}

<?php

declare(strict_types=1);

namespace Psl\Type\Internal;

use Override;
use Psl\Type\Exception\AssertException;
use Psl\Type\Exception\CoercionException;
use Psl\Type\Type;

use function is_callable;

/**
 * Type implementation for `callable`.
 *
 * @extends Type<callable>
 */
final readonly class CallableType extends Type
{
    #[Override]
    public function assert(mixed $value): callable
    {
        if (is_callable($value)) {
            return $value;
        }

        throw AssertException::withValue($value, $this->toString());
    }

    #[Override]
    public function coerce(mixed $value): callable
    {
        if (is_callable($value)) {
            return $value;
        }

        throw CoercionException::withValue($value, $this->toString());
    }

    #[Override]
    public function matches(mixed $value): bool
    {
        return is_callable($value);
    }

    #[Override]
    public function toString(): string
    {
        return 'callable';
    }
}

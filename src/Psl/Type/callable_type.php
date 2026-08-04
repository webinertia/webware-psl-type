<?php

declare(strict_types=1);

namespace Psl\Type;

/**
 * @pure
 *
 * @mago-expect lint:function-name
 * @return TypeInterface<callable>
 *
 * @api
 */
function callable_type(): TypeInterface
{
    return new Internal\CallableType();
}

<?php

declare(strict_types=1);

(static function (): void {
    $functions = [
        'Psl\Type\callable_type' => __DIR__ . '/Type/callable_type.php',
    ];

    foreach ($functions as $function => $path) {
        if (function_exists($function)) {
            continue;
        }

        require_once $path;
    }
})();

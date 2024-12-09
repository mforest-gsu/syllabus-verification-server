<?php

declare(strict_types=1);

use Gsu\SyllabusVerification\Kernel;

require_once dirname(__DIR__) . '/vendor/autoload_runtime.php';

return fn (array $context) => new Kernel(
    is_string($context['APP_ENV'] ?? null) ? $context['APP_ENV'] : throw new \RuntimeException(),
    (bool) $context['APP_DEBUG']
);

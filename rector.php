<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([__DIR__.'/config', __DIR__.'/src', __DIR__.'/tests'])
    ->withPhpSets()
    ->withComposerBased(doctrine: true, symfony: true, phpunit: true)
    ->withAttributesSets(doctrine: true, symfony: true, phpunit: true)
    ->withSkip([
        \Rector\Php84\Rector\MethodCall\NewMethodCallWithoutParenthesesRector::class,
        \Rector\Php84\Rector\Class_\DeprecatedAnnotationToDeprecatedAttributeRector::class,
    ]);

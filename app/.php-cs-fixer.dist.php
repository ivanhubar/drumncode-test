<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var')
    ->exclude('vendor')
    ->exclude('templates')
    ->exclude('public')
    ->exclude('migrations')
    ->exclude('config')
    ->exclude('bin')
    ->exclude('assets')
    ->exclude('translations');

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        '@PSR12' => true,
    ])
    ->setFinder($finder);

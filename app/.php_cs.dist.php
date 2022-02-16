<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create();

$config = new PhpCsFixer\Config();

$config->setRules(['@PSR12' => true,])
    ->setFinder($finder)
    ->setCacheFile( 'var/cache/.php-cs-fixer.cache');

return $config;
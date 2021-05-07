<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude('assets')
    ->exclude('vendor')
    ->in(__DIR__ . '/widgets')
;

$config = new PhpCsFixer\Config();
return $config->setRules([
	'@PSR12' => true,
])
    ->setFinder($finder)
    ;

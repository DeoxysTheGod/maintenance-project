<?php

$finder = PhpCsFixer\Finder::create()
  ->in(__DIR__)
  ->exclude('var')
  ->exclude('vendor');

return (new PhpCsFixer\Config())
  ->setRules([
    '@Symfony' => true,
    'array_syntax' => ['syntax' => 'short'],
    'binary_operator_spaces' => ['default' => 'align_single_space_minimal'],
    'no_unused_imports' => true,
  ])
  ->setFinder($finder);

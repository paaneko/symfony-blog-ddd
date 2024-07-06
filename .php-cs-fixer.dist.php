<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var')
;

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        'array_indentation' => true,
        'blank_line_after_opening_tag' => true,
        'blank_line_after_namespace' => true,
        'combine_consecutive_issets' => true,
        'concat_space' => ['spacing' => 'one'],
        'declare_strict_types' => true,
        'explicit_string_variable' => true,
        'align_multiline_comment' => true,
        'no_unused_imports' => true
    ])
    ->setFinder($finder)
;

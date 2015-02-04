<?php
/**
 * PHP-CS-Fixer settings
 * https://github.com/fabpot/PHP-CS-Fixer
 */

$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->in('src/App')
;

return Symfony\CS\Config\Config::create()
    ->fixers([
        'encoding',
        'linefeed',
        'indentation',
        'trailing_spaces',
        'unused_use',
        'object_operator',
        // 'phpdoc_params',
        'visibility',
        'short_tag',
        'php_closing_tag',
        // 'return',
        'extra_empty_lines',
        'braces',
        'lowercase_constants',
        'lowercase_keywords',
        'include',
        'function_declaration',
        'controls_spaces',
        'spaces_cast',
        'psr0',
        'one_class_per_file',
        'elseif',
        'eof_ending',
        'short_array_syntax',
        'multiline_array_trailing_comma',
    ])
    ->finder($finder)
;

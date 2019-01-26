<?php

$header = '
This file is part of the Agate Apps package.

(c) Alexandre Rock Ancelet <pierstoval@gmail.com> and Studio Agate.

For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.
';

$finder = PhpCsFixer\Finder::create()
    ->exclude([
        'vendor',
    ])
    ->notName('FPDF.php')
    ->notName('PDF.php')
    ->notName('PdfManager.php')
    ->notName('bootstrap.php')
    ->in([
        __DIR__.'/src/',
        __DIR__.'/tests/',
    ])
;

return PhpCsFixer\Config::create()
    ->setRules([
        'header_comment' => [
            'header' => $header,
        ],
        // Enabled rules
        '@DoctrineAnnotation'             => true,
        '@Symfony'                        => true,
        '@Symfony:risky'                  => true,
        '@PHP56Migration'                 => true,
        '@PHP70Migration'                 => true,
        '@PHP70Migration:risky'           => true,
        '@PHP71Migration'                 => true,
        '@PHP71Migration:risky'           => true,
        'compact_nullable_typehint'       => true,
        'fully_qualified_strict_types'    => true,
        'heredoc_to_nowdoc'               => true,
        'linebreak_after_opening_tag'     => true,
        'logical_operators'               => true,
        'mb_str_functions'                => true,
        'native_function_invocation'      => true,
        'no_null_property_initialization' => true,
        'no_php4_constructor'             => true,
        'no_short_echo_tag'               => true,
        'no_superfluous_phpdoc_tags'      => true,
        'no_useless_else'                 => true,
        'no_useless_return'               => true,
        'ordered_imports'                 => true,
        'simplified_null_return'          => true,
        'strict_param'                    => true,
        'array_syntax'                    => [
            'syntax' => 'short',
        ],
        // Overrides default doctrine rule using ":" as character
        'doctrine_annotation_array_assignment' => [
            'operator' => '=',
        ],
        'multiline_whitespace_before_semicolons' => [
            'strategy' => 'new_line_for_chained_calls',
        ],

        // Disabled rules
        'increment_style' => false,         // Because "++$i" is not always necessary…
        'non_printable_character' => false, // Because I love using non breakable spaces in test methods ♥
    ])
    ->setRiskyAllowed(true)
    ->setIndent('    ')
    ->setLineEnding("\n")
    ->setUsingCache(true)
    ->setFinder($finder)
;

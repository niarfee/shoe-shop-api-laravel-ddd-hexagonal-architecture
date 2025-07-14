<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in(__DIR__)
    ->name('*.php')
    ->notName('*.blade.php')
    ->notName('_ide_helper*.php')
    ->exclude('vendor')
    ->exclude('storage')
    ->exclude('bootstrap/cache');

return (new Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR12' => true,
        'declare_strict_types' => true, // declare(strict_types=1);
        'no_unused_imports'    => true, // Eliminates unused ‘use’.
        // 'ordered_imports' => ['sort_algorithm' => 'alpha'], // Already does it “namespaceResolver.sortAlphabetically”: true, from visual code settings.json.
        'array_syntax' => ['syntax' => 'short'], // Forces the use of arrays with [] instead of array().
        // 'binary_operator_spaces' => ['default' => 'align_single_space_minimal'], // Aligns binary operators (such as = or =>) vertically with a single space.
        'no_extra_blank_lines' => [
            'tokens' => ['extra'], // Only one blank line
        ],
        'single_quote' => true, // Force use of single quotes ‘ instead of double quotes " unless necessary.
        'trailing_comma_in_multiline' => [ // Adds a trailing comma in multi-line arrays.
            'elements' => [
                'arrays',
                'arguments',
                'parameters',
            ],
        ],
        'phpdoc_align' => ['align' => 'left'], // Aligns the @param, @return, etc. in the PHPDoc.
        'phpdoc_order' => true, // Automatically sorts PHPDoc tags (@param, @return, @throws, etc.).
        // 'native_function_invocation' => ['include' => ['@all']], // Automatically adds the global \ namespace to native functions (\strlen(), \count(), etc.).
        'array_indentation' => true, // Auto indentation
        'class_attributes_separation' => [
            'elements' => ['method' => 'one'], // Line break between two methods.
        ],
        'ordered_class_elements' => [
            'order' => [
                'use_trait',
                'case',
                'constant_public',
                'constant_protected',
                'constant_private',
                'property_public',
                'property_protected',
                'property_private',
                'construct',
                'method_public_static',
                'method_protected_static',
                'method_private_static',
                'method_public',
                'method_protected',
                'method_private',
            ],
        ],
    ])
    ->setFinder($finder);

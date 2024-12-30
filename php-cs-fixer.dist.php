<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in(__DIR__)
    ->exclude('vendor') // Exclude vendor directory
    ->name('*.php');    // Include only PHP files

return (new Config())
    ->setRules([
        // Formatting rules
        'no_whitespace_in_blank_line' => true,          // Remove whitespace from blank lines
        'indentation_type'            => true,                    // Ensure consistent indentation (spaces/tabs)
        'trim_array_spaces'           => true,                   // Remove extra spaces inside arrays

        // Cleanup rules
        'no_unused_imports'      => true,                   // Remove unused imports
        'ordered_imports'        => ['sort_algorithm' => 'alpha'], // Sort imports alphabetically
        'phpdoc_trim'            => true,                         // Remove unnecessary blank lines in PHPDoc
        'no_trailing_whitespace' => true,              // Remove trailing whitespace
        'no_extra_blank_lines'   => true,                // Remove extra blank lines

        // Code style rules
        'cast_spaces'            => ['space' => 'single'],        // Single space after type casts
        'lowercase_keywords'     => true,                 // PHP keywords in lowercase
        'method_argument_space'  => ['on_multiline' => 'ensure_fully_multiline'], // Fix spacing in method arguments
        'binary_operator_spaces' => [
            'default' => 'align_single_space_minimal', // Align binary operators minimally
        ],
        'single_quote' => true,                       // Convert double quotes to single quotes where possible

        // Code structure rules
        'class_attributes_separation' => [
            'elements' => ['method' => 'one'],        // Ensure one blank line between class methods
        ],
        'visibility_required' => [
            'elements' => ['property', 'method'],     // Require visibility for properties and methods
        ],
        'blank_line_after_namespace'  => true,         // Add a blank line after the namespace declaration
        'blank_line_before_statement' => [
            'statements' => ['return'],               // Add blank line before return statements
        ],

        // Miscellaneous rules
        'concat_space'                => ['spacing' => 'one'],       // Ensure single space around concatenation operator
        'ternary_operator_spaces'     => true,           // Fix spacing for ternary operators
        'trailing_comma_in_multiline' => [
            'elements' => ['arrays'],                // Add trailing commas in multiline arrays
        ],
    ])
    ->setFinder($finder);

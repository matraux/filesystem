<?php declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

return (new Config())
	->setCacheFile(sys_get_temp_dir() . '/.php-cs-fixer.cache')
	->setRiskyAllowed(true)
	->setIndent("\t")
	->setLineEnding("\n")
	->setRules([
		'@PER-CS' => true,
		'@autoPHPMigration' => true,
		'blank_line_after_opening_tag' => false,
		'linebreak_after_opening_tag' => false,
		'single_blank_line_at_eof' => true,
		'no_unused_imports' => true,
		'use_arrow_functions' => true,
		'ordered_imports' => [
			'imports_order' => [
				'class',
				'function',
				'const'
			],
			'sort_algorithm' => 'alpha',
		],
		'single_line_after_imports' => true,
		'single_import_per_statement' => [
			'group_to_single_imports' => true,
		],
		'fully_qualified_strict_types' => [
			'import_symbols' => true,
		],
		'no_superfluous_phpdoc_tags' => [
			'allow_hidden_params' => true,
			'allow_mixed' => true,
			'remove_inheritdoc' => true,
		],
		'no_useless_else' => true,
		'no_redundant_readonly_property' => true,
		'ordered_class_elements' => [
			'order' => [
				'use_trait',
				'case',
				'constant_public',
				'constant_protected',
				'constant_private',
				'property_public_static',
				'property_public',
				'property_protected_static',
				'property_protected',
				'property_private_static',
				'property_private',
				'construct',
				'destruct',
				'method_public_abstract_static',
				'method_public_static',
				'method_public_abstract',
				'method_public',
				'method_protected_abstract_static',
				'method_protected_static',
				'method_protected_abstract',
				'method_protected',
				'method_private_abstract_static',
				'method_private_static',
				'method_private_abstract',
				'method_private',
				'magic',
			],
			'sort_algorithm' => 'none',
		],
		'class_attributes_separation' => [
			'elements' => [
				'const' => 'one',
				'property' => 'one',
				'method' => 'one',
				'trait_import' => 'none',
				'case' => 'none',
			],
		],
	])
	->setFinder((new Finder())->in([
		__DIR__ . '/src',
		__DIR__ . '/tests',
	]))
;

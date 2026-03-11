<?php declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

return (new Config())
	->setCacheFile(sys_get_temp_dir() . '/.php-cs-fixer.cache')
	->setRiskyAllowed(false)
	->setIndent("\t")
	->setLineEnding("\n")
	->setRules([
		'@PER-CS' => true,
		'blank_line_after_opening_tag' => false,
		'linebreak_after_opening_tag' => false,
		'single_blank_line_at_eof' => true,
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
	->setFinder(new Finder()->in([
		__DIR__ . '/src',
		__DIR__ . '/tests',
	]))
;

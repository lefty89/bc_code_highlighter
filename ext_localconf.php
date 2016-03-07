<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'BC.' . $_EXTKEY,
	'Preview',
	array(
		'Preview' => 'render',
	),
	// non-cacheable actions
	array(
		'Preview' => 'render',
	)
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'BC.' . $_EXTKEY,
	'Result',
	array(
		'Result' => 'show',
	),
	// non-cacheable actions
	array(
		'Result' => 'show',
	)
);
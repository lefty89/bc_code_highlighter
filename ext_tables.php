<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Bugcluster Code Highlighter');

// underscored extension name
$extensionName = strtolower(\TYPO3\CMS\Core\Utility\GeneralUtility::underscoredToUpperCamelCase($_EXTKEY));

/**
 * Flexform for Preview Plugin
 */
$TCA['tt_content']['types']['list']['subtypes_addlist'][$extensionName.'_preview'] = 'pi_flexform';
TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($extensionName.'_preview', 'FILE:EXT:'.$_EXTKEY . '/Configuration/FlexForm/flexform_preview.xml');

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Preview',
	'Bugcluster Code Highlighter Preview'
);

/**
 * Flexform for Preview Plugin
 */
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Result',
	'Bugcluster Code Highlighter Result'
);
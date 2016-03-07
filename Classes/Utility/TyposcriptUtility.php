<?php

namespace BC\BcCodeHighlighter\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Service\TypoScriptService;

/**
 * Custom Storage backend
 */
class TyposcriptUtility {

	/**
	 * get full typoscript configuration for this extension
	 *
	 * @return array
	 */
	public static function getConfiguration() {

		/** @var \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager */
		$objectManager = GeneralUtility::makeInstance(ObjectManager::class);

		/** @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager */
		$configurationManager = $objectManager->get(ConfigurationManagerInterface::class);

		/** @var array $typoscript */
		$typoscript = $configurationManager->getConfiguration(
			ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT,
			'BcCodeHighlighter'
		);

		/** @var \TYPO3\CMS\Extbase\Service\TypoScriptService $typoScriptService */
		$typoScriptService = GeneralUtility::makeInstance(TypoScriptService::class);

		// switch to no dot notation
		$rawTs = $typoScriptService->convertTypoScriptArrayToPlainArray($typoscript);

		return $rawTs['plugin']['tx_bccodehighlighter'];
	}
}
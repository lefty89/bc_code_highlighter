<?php

namespace BC\BcCodeHighlighter\Utility;

/***************************************************************
 *  Copyright notice
 *
 *  (C) 2016 Lefty (fb.lefty@web.de)
 *
 *  This script is part of the Typo3 project. The Typo3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this script. If not, see <http://www.gnu.org/licenses/>.
 *
 ***************************************************************/

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Service\TypoScriptService;

/**
 * @author Lefty (fb.lefty@web.de)
 * @package TYPO3
 * @subpackage bc_code_highlighter
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
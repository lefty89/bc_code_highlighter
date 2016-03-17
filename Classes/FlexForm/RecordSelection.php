<?php

namespace BC\BcCodeHighlighter\FlexForm;

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

use BC\BcCodeHighlighter\Utility\StandaloneUtility;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;

/**
 * @author Lefty (fb.lefty@web.de)
 * @package TYPO3
 * @subpackage bc_code_highlighter
 */
class RecordSelection {

	/**
	 * adds required resources (js/css)
	 */
	private function addResources()
	{
		/** @var string $extPath */
		$extPath = ExtensionManagementUtility::siteRelPath("bc_code_highlighter").'Resources/Public/';
		/** @var string $absPath */
		$absPath = GeneralUtility::getFileAbsFileName($extPath);
		/** @var string $relPathFromHere */
		$relPathToExt = PathUtility::getRelativePathTo($absPath);

		/** @var \TYPO3\CMS\Core\Page\PageRenderer $pageRenderer */
		$pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);

		// required css files
		$pageRenderer->addCssFile($relPathToExt.'css/module.css');

		// add inline blobby config
		$pageRenderer->addRequireJsConfiguration($this->getInlineConfig($relPathToExt));

		// required javascript files
		$pageRenderer->addJsFile($relPathToExt.'js/module.js');

	}

	/**
	 * generate inline config
	 * @param string $relPathToExt
	 * @return string
	 */
	private function getInlineConfig($relPathToExt)
	{
		$config = array(
			// alias libraries paths
			'paths' => array(
				'angular' => $relPathToExt.'js/angular.min',
			),
			// angular does not support AMD out of the box, put it in a shim
			'shim' => array(
				'angular' => array(
					'exports' => 'angular'
				)
			)
		);

		return $config;
	}

	/**
	 * @param array $fConfig
	 * @param $fObj
	 *
	 * @return string
	 */
	public function renderSelector(&$fConfig, $fObj) {

		// add css and js
		$this->addResources();

		return StandaloneUtility::renderStandaloneView('EXT:bc_code_highlighter/Resources/Private/Backend/Select.html',array(
			'fConfig' => $fConfig,	// flexform data includes hidden value field
		));
	}
}  
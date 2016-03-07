<?php

namespace BC\BcCodeHighlighter\FlexForm;

/**
 * User: Lefty
 * Date: 31.01.2015
 * Time: 15:26
 * @package BC\BcCodeHighlighter\FlexForm
 */
use BC\BcCodeHighlighter\Utility\StandaloneUtility;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;

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
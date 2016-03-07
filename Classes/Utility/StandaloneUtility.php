<?php
namespace BC\BcCodeHighlighter\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Fluid\View\StandaloneView;

class StandaloneUtility {

	/**
	 * render template
	 *
	 * @param string $templateFile
	 * @param array $variables
	 * @return string
	 */
	public static function renderStandaloneView($templateFile, $variables = array()) {

		/** @var array $frameworkConf */
		$frameworkConf = TyposcriptUtility::getConfiguration();

		/** @var \TYPO3\CMS\Extbase\Object\ObjectManager $objectManager */
		$objectManager = GeneralUtility::makeInstance(ObjectManager::class);

		/** @var \TYPO3\CMS\Fluid\View\StandaloneView $standaloneView */
		$standaloneView = $objectManager->get(StandaloneView::class);

		/** @var string $layoutPath */
		$layoutPath = GeneralUtility::getFileAbsFileName($frameworkConf['view']['layoutRootPath']);

		/** @var string $templatePathAndFilename */
		$templatePathAndFilename = GeneralUtility::getFileAbsFileName($templateFile);

		$standaloneView->setTemplatePathAndFilename($templatePathAndFilename);
		$standaloneView->setLayoutRootPaths(array($layoutPath));
		$standaloneView->assignMultiple($variables);

		return $standaloneView->render();
	}
}
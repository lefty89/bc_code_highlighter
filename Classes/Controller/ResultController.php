<?php

namespace BC\BcCodeHighlighter\Controller;

/**
 *
 * User: Lefty
 * Date: 31.01.2015
 * Time: 13:21
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;


/**
 * Class RenderController
 * @package BC\BcCodeHighlighter\Controller
 */
class ResultController extends ActionController {

	/**
	 * @var \TYPO3\CMS\Core\Configuration\FlexForm\FlexFormTools
	 * @inject
	 */
	protected $flexFormTools;

	/**
	 * @param int $cuid
	 */
	public function showAction($cuid)
	{
		/** @var array $record */
		$record = BackendUtility::getRecord('tt_content', $cuid, 'pi_flexform');

		if (!empty($record)) {

			/** @var array $flexform */
			$flexform = GeneralUtility::xml2array($record['pi_flexform']);

			/** @var array $flex */
			$flex = $this->flexFormTools->getArrayValueByPath('data/sDEF/lDEF/settings.custom/vDEF', $flexform);

			/** @var array $data */
			$data = json_decode(urldecode((base64_decode($flex))));

			// adds html, js and ccs
			$this->addSources($data);
		}
	}

	/**
	 * @param Object $item
	 * @return string
	 */
	private function getCode($item)
	{
		return (!$item->external) ? $item->code : (file_get_contents($item->url) ?: "No Code loaded");
	}

	/**
	 * @param array $data
	 */
	private function addSources($data)
	{
		/** @var \TYPO3\CMS\Core\Page\PageRenderer $pr */
		$pr = $GLOBALS['TSFE']->getPageRenderer();

		/** @var Object $d */
		foreach ($data as $d) {

			switch($d->ext) {
				case 'css': {
					$pr->addCssInlineBlock('css', $this->getCode($d)); break;
				}
				case 'js': {
					$pr->addJsInlineCode('js', $this->getCode($d)); break;
				}
				case 'html': {
					$this->view->assign('markup', $this->getCode($d));  break;
				}
			}
		}
	}
}
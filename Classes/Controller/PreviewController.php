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
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;


/**
 * Class RenderController
 * @package BC\BcCodeHighlighter\Controller
 */
class PreviewController extends ActionController {

	/**
	 * render
	 */
	public function renderAction() {

		// add css and js
		$this->addResources();

		if (boolval($this->settings['result'])) {
			/** @var \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer $cObj */
			$cObj = $this->configurationManager->getContentObject();
			// assign uid of flexform element
			$this->view->assign('cuid', $cObj->data['uid']);
		}

		$this->view->assign('sources', $this->getSources());

	}

	/**
	 * @return array
	 */
	private function getSources()
	{
		$sources = array();

		/** @var string $flexValue */
		$flexValue = $this->settings['custom'];

		/** @var Object $data */
		$data = json_decode(urldecode((base64_decode($flexValue))));

		/** @var Object $d */
		foreach ($data as $d) {

			// replace line breaks and encode
			$code  = htmlentities($this->getCode($d));
			$raw = str_replace(array("\r\n", "\r", "\n"), "&#x000D;", $code);

			if ($d->show) {
				array_push($sources, array(
					'name' => $d->name,
					'code' => $raw,
					'ext'  => $d->ext
				));
			}
		}

		return $sources;
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
	 * adds required resources (js/css)
	 */
	private function addResources()
	{
		/** @var string $extPath */
		$extPath = ExtensionManagementUtility::siteRelPath("bc_code_highlighter").'Resources/Public/';

		/** @var \TYPO3\CMS\Core\Page\PageRenderer $pr */
		$pr = $GLOBALS['TSFE']->getPageRenderer();

		// required css files
		$pr->addCssFile($extPath.'css/prism-cb.css');
		$pr->addCssFile($extPath.'css/style.css');

		// required javascript files
		$pr->addJsFooterFile($extPath.'js/prism.js');
		$pr->addJsFooterFile($extPath.'js/main.js');
	}
}
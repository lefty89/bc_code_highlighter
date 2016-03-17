<?php

namespace BC\BcCodeHighlighter\Controller;

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

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;


/**
 * @author Lefty (fb.lefty@web.de)
 * @package TYPO3
 * @subpackage bc_code_highlighter
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
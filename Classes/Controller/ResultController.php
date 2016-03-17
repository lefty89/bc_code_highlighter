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

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;


/**
 * @author Lefty (fb.lefty@web.de)
 * @package TYPO3
 * @subpackage bc_code_highlighter
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

		/** @var Object $v */
		foreach ($data as $k => $v) {

			switch($v->ext) {
				case 'css': {
					$pr->addCssInlineBlock("css-$k", $this->getCode($v)); break;
				}
				case 'js': {
					$pr->addJsInlineCode("js-$k", $this->getCode($v)); break;
				}
				case 'html': {
					$this->view->assign('markup', $this->getCode($v));  break;
				}
			}
		}
	}
}
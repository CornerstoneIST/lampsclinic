<?php
/**
 * IDEALIAGroup srl
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.idealiagroup.com/magento-ext-license.html
 *
 * @category   IG
 * @package    IG_404ToSearch
 * @copyright  Copyright (c) 2012 IDEALIAGroup srl (http://www.idealiagroup.com)
 * @license    http://www.idealiagroup.com/magento-ext-license.html
 */
 
class IG_404ToSearch_Helper_Data extends Mage_Core_Helper_Abstract
{
	const XML_PATH_GENERAL_ENABLED = 'ig_404tosearch/general/enabled';
	const XML_PATH_GENERAL_TEMPLATE = 'ig_404tosearch/general/template';
	const XML_PATH_GENERAL_TITLE = 'ig_404tosearch/general/page_title';
	
	/**
	 * Return true if service is enabled
	 * 
	 * @return bool
	 */
	public function getIsEnabled()
	{
		return (bool)Mage::getStoreConfig(self::XML_PATH_GENERAL_ENABLED);
	}
	
	/**
	 * Get search mode
	 * 
	 * @return string
	 */
	public function getTemplate()
	{
		return Mage::getStoreConfig(self::XML_PATH_GENERAL_TEMPLATE);
	}
	
	/**
	 * Get title
	 * 
	 * @return string
	 */
	public function getTitle()
	{
		return Mage::getStoreConfig(self::XML_PATH_GENERAL_TITLE);
	}
	
	/**
	 * Convert template
	 * 
	 * @param string $path
	 * @param array $qs
	 * @return string
	 */
	public function getQueryString($path, $qs)
	{
		$path = str_replace('ig_404tosearch/index/noRoute/q/', '', $path);
		
		$return = $this->getTemplate();
		if (!preg_match_all("/\{\{([^\}]+)\}\}/", $return, $matches))
			return null;


		$pathInfo = pathinfo($path);
		$path = $pathInfo['dirname'].'/'.$pathInfo['filename'];
		$pathA = explode('/', $path);

		foreach($matches[1] as $param)
		{
			$v = '';
			
			if ($param == '_url_')
			{
				$v = $path;
			}
			elseif (preg_match("/_url:(\-*\d+)_/", $param, $matches))
			{
				$n = intval($matches[1]);
				$start = $n>=0 ? $n+1 : count($pathA)+$n;

				for ($i=$start; $i<count($pathA); $i++)
				{
					$v .= $pathA[$i].' ';
				}
			}
			elseif (preg_match("/_url\[(\-*\d+)\]_/", $param, $matches))
			{
				$n = intval($matches[1]);
				$start = $n>=0 ? $n+1 : count($pathA)+$n;

				$v = $pathA[$start];
			}
			elseif (isset($qs[$param]))
			{
				$v = $qs[$param];
			}
			
			$v = trim(preg_replace('/\W/', ' ', $v));
			$return = str_replace('{{'.$param.'}}', $v, $return);
		}
		
		$return = trim($return);
		return $return;
	}
}
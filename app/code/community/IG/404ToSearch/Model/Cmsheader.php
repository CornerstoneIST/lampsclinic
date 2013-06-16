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

class IG_404ToSearch_Model_Cmsheader extends Mage_Core_Model_Config_Data
{
	public function getvalue()
	{
		return Mage::helper('ig_404tosearch')->__('Create a CMS Block named "ig_404tosearch"');
	}
}

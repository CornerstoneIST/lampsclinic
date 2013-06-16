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

require_once "Mage/Cms/controllers/IndexController.php";
class IG_404ToSearch_IndexController extends Mage_Cms_IndexController
{
	/**
     * Render CMS 404 Not found page
     *
     * @param string $coreRoute
     */
    public function noRouteAction($coreRoute = null)
    {
    	$helper = Mage::helper('ig_404tosearch');
		
    	if (!$helper->getIsEnabled())
			return parent::noRouteAction($coreRoute);
		
		$urlInfo = parse_url($_SERVER['REQUEST_URI']);
		
		$qs = $helper->getQueryString($urlInfo['path'], $_REQUEST);
		$queryHelper = Mage::helper('catalogsearch');
		
		$this->getRequest()->setParam($queryHelper->getQueryParamName(), $qs);
		
		$query = $queryHelper->getQuery();
        $query->setStoreId(Mage::app()->getStore()->getId());

        if ($query->getQueryText())
		{
            if (Mage::helper('catalogsearch')->isMinQueryLength())
			{
                $query->setId(0)
                    ->setIsActive(1)
                    ->setIsProcessed(1);
            }
            else
			{
                if ($query->getId())
				{
                    $query->setPopularity($query->getPopularity()+1);
                }
                else
				{
                    $query->setPopularity(1);
                }

                if ($query->getRedirect())
				{
                    $query->save();
                    $this->getResponse()->setRedirect($query->getRedirect());
                    return;
                }
                else
				{
                    $query->prepare();
                }
            }

            Mage::helper('catalogsearch')->checkNotes();
			
			$this->getResponse()->setHttpResponseCode(404);

            $this->loadLayout();
			$this->getLayout()->getBlock('head')->setTitle($helper->getTitle());
            $this->_initLayoutMessages('catalog/session');
            $this->_initLayoutMessages('checkout/session');
            $this->renderLayout();

            if (!Mage::helper('catalogsearch')->isMinQueryLength())
			{
                $query->save();
            }
        }
        else
		{
            $this->_redirectReferer();
        }
    }
}

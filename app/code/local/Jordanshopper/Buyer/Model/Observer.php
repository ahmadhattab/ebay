<?php

// observer for add to cart which can enable one item to added for one seller
class jordanshopper_Buyer_Model_Observer {

	public function logCartAdd($observer) 
	{
		$proId = Mage::app()->getRequest()->getParam('product');
		$quote = Mage::getSingleton('checkout/session')->getQuote();
		//$cartItems = $quote->getAllVisibleItems();
		$session = Mage::getSingleton('core/session');
		$items = $quote->getAllVisibleItems();
		$product = Mage::getModel('catalog/product')->load($proId);
		foreach ($items as $item) 
		{
			$proCart = Mage::getModel('catalog/product')->load($item->getProductId());
			if ($product->getSellerId() != $proCart->getSellerId())
			{
				$session->addError(Mage::helper('core')->__("You can't add it to your cart from different seller"));
				Mage::app()->getFrontController()->getResponse()->setRedirect($_SERVER['HTTP_REFERER']);
				Mage::app()->getResponse()->sendResponse();
				exit;
			}
		}
	}

}

<?php
class Jordanshopper_Seller_Model_Observer
{
	public function setFeedbackEntity($observer)
	{
		$order = $observer->getEvent()->getOrder(); 
    	$order_id = $order->getData('increment_id');
    	$buyer_id = Mage::getSingleton('customer/session')->getId();
    	$quote = Mage::getSingleton('checkout/session')->getQuote();
    	$items = $quote->getAllVisibleItems();
    	foreach ($items as $item) 
		{
			$_product = Mage::getModel('catalog/product')->load($item->getProductId());
			$seller_id = $_product->getSellerId();
		} 
    	$status = 0;
    	$sellerModel = Mage::getModel('seller/feedback');
    	$sellerModel->setOrderNumber($order_id);
    	$sellerModel->setBuyerId($buyer_id);
    	$sellerModel->setSellerId($seller_id);
    	$sellerModel->setStatus($status);
    	$sellerModel->save();
	}
}
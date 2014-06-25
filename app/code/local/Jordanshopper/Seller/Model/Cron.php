<?php
class Jordanshopper_Seller_Model_Cron extends Mage_Core_Model_Abstract
{
	public function checkExpired()
	{
		$date = date('Y-m-d');
		$products = Mage::getModel('catalog/product')->getCollection()
		->addAttributeToSelect('*')
		->addAttributeToFilter('news_from_date', array('neq' => ''))
		->addAttributeToFilter('news_to_date', array('lt' => $date))
		->addAttributeToFilter('status', array('eq' => 1));
		foreach ($products as $product)
		{
			$product->setWebsiteIDs(array(1));
			$product->setStoreId(0);
			$product->setStatus(2);
			$product->save();
			echo $product->getId();
			echo '<br />';
		}
		echo 'Cron Excuted';
	}
}

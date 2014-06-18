<?php
class Jordanshopper_Buyer_Block_Featured extends Mage_Core_Block_Template
{
	public function __construct()
	{
		parent::__construct();
		$this->setTemplate('jordanshopper/buyer/featured.phtml');
	}
	
	public function getFeatured()
	{
		$collection = Mage::getModel('catalog/product')->getCollection()
						->addAttributeToSelect('featured')
						->addAttributeToFilter('status', array('eq' => 1))
						->addAttributeToFilter('featured', array('eq' => 1));
		$collection->getSelect()->order('rand()');
		$collection->getSelect()->limit(20);
		//echo '<pre>';
		//print_r($collection->getData());
		return $collection;
	}
}
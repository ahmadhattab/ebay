<?php
class Jordanshopper_Buyer_Block_Selling extends Mage_Core_Block_Template
{
	public function __construct()
	{
		parent::__construct();
		$this->setTemplate('jordanshopper/buyer/selling.phtml');
	}
	
	public function getSelling()
	{
		$collection = Mage::getModel('catalog/product')->getCollection()
						->addAttributeToSelect('selling')
						->addAttributeToFilter('selling', array('eq' => 1));
		$collection->getSelect()->order('rand()');
		$collection->getSelect()->limit(20);
		return $collection;
	}
}
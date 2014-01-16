<?php

class Jordanshopper_Seller_Block_Adminhtml_Customer_Edit_Listing
extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct()
	{
		Mage::getSingleton('admin/session')->setSellerId($this->getRequest()->getParam('id'));
		$this->_controller = 'adminhtml_customer_edit_listing';
		$this->_blockGroup = 'seller';
		$this->_headerText = Mage::helper('customer')->__('Product Listing');
		parent::__construct();
		$this->_removeButton('add');
	}
}

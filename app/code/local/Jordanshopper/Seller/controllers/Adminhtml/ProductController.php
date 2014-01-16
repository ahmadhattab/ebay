<?php 
class Jordanshopper_Seller_Adminhtml_ProductController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction()
	{
		$this->loadLayout();
		$this->_addContent($this->getLayout()->createBlock('seller/adminhtml_customer_edit_listing'));
		$this->renderLayout();
	}
	
	public function gridAction()
	{
		$this->loadLayout();
		$this->getResponse()->setBody(
			$this->getLayout()->createBlock('seller/adminhtml_customer_edit_listing_grid')->toHtml()
		);
	}
}
?>
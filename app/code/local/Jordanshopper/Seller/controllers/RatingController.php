<?php
class Jordanshopper_Seller_RatingController extends Mage_Core_Controller_Front_Action
{
	public function indexAction()
	{
		$coreSession = Mage::getSingleton('core/session');
		$customerSession = Mage::getSingleton('customer/session');
		$feedbackModel = Mage::getModel('seller/feedback')->load($this->getRequest()->getParam('order_id'),'order_number');
		if ($feedbackModel->getStatus() == 0 && $feedbackModel->getData() && $customerSession->getId() == $feedbackModel->getBuyerId())
		{
			$this->loadLayout();
			$this->renderLayout();
		}
		else
		{
			$coreSession->addError("Can't add rating");
			$this->_redirect('sales/order/history');
		}
	}
	
	public function sellerPostAction()
	{
		echo '<pre>';
		print_r($this->getRequest()->getParams());die;
	}
}
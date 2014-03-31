<?php
class Jordanshopper_Seller_RatingController extends Mage_Core_Controller_Front_Action
{
	public function indexAction()
	{
		$coreSession = Mage::getSingleton('core/session');
		$customerSession = Mage::getSingleton('customer/session');
		$feedbackModel = Mage::getModel('seller/feedback')->load($this->getRequest()->getParam('order_id'),'order_number');
		if ($feedbackModel->getStatus() == 1 && $feedbackModel->getData() && $customerSession->getId() == $feedbackModel->getBuyerId())
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
		$session = Mage::getSingleton('core/session');
		if ($this->getRequest()->isPost())
		{
			$data = $this->getRequest()->getParams();
			$feedbackModel = Mage::getModel('seller/feedback')->load($data['order_number'], 'order_number');
			if ($feedbackModel->getData() && $feedbackModel->getStatus() == 1)
			{
				$sellerFeedbackModel = Mage::getModel('seller/feedbackseller');
				$sellerFeedbackModel->setSellerId($data['seller_id']);
				$sellerFeedbackModel->setOrderNumber($data['order_number']);
				$sellerFeedbackModel->setFeedback($data['feedback']);
				$sellerFeedbackModel->setItemDesc($data['desc']);
				$sellerFeedbackModel->setPrice($data['price']);
				$sellerFeedbackModel->setShip($data['ship']);
				$sellerFeedbackModel->setCommunication($data['comm']);
				$sellerFeedbackModel->setText($data['brief']);
				$sellerFeedbackModel->save();
				$feedbackModel->setStatus(2);
				$feedbackModel->save();
				$session->addSuccess($this->__('Thank you for your rating'));
				$this->_redirect('sales/order/history');
				
			}
			else
			{
				$session->addError($this->__("You can't add rating for this seller"));
				$this->_redirect('sales/order/history');
			}
		}
		else
		{
			$this->_redirect('sales/order/history');
		}
		
	}
	
	public function buyerAction()
	{
		$coreSession = Mage::getSingleton('core/session');
		$customerSession = Mage::getSingleton('customer/session');
		$feedbackModel = Mage::getModel('seller/feedback')->load($this->getRequest()->getParam('order_id'),'order_number');
		if ($feedbackModel->getStatus() == 2 && $feedbackModel->getData() && $customerSession->getId() == $feedbackModel->getSellerId())
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
	
	public function buyerPostAction()
	{
		$session = Mage::getSingleton('core/session');
		if ($this->getRequest()->isPost())
		{
			$data = $this->getRequest()->getParams();
			$feedbackModel = Mage::getModel('seller/feedback')->load($data['order_number'], 'order_number');
			if ($feedbackModel->getData() && $feedbackModel->getStatus() == 2)
			{
				$buyerFeedbackModel = Mage::getModel('seller/feedbackbuyer');
				$buyerFeedbackModel->setBuyerId($data['buyer_id']);
				$buyerFeedbackModel->setOrderNumber($data['order_number']);
				$buyerFeedbackModel->setFeedback($data['feedback']);
				$buyerFeedbackModel->setItemPay($data['pay']);
				$buyerFeedbackModel->setCommunication($data['comm']);
				$buyerFeedbackModel->setText($data['brief']);
				$buyerFeedbackModel->save();
				$feedbackModel->setStatus(3);
				$feedbackModel->save();
				$session->addSuccess($this->__('Thank you for your rating'));
				$this->_redirect('customer/activity/selling');
		
			}
			else
			{
				$session->addError($this->__("You can't add rating for this seller"));
				$this->_redirect('sales/order/history');
			}
		}
	}
	
	public function listAction()
	{
		$customerSession = Mage::getSingleton('customer/session');

		$sellerId = $this->getRequest()->getParam('id');
		if ($sellerId)
		{
			$sellerData = Mage::getModel('customer/customer')->load($sellerId);
		}
		else
		{
			$sellerData = Mage::getModel('customer/customer')->load($customerSession->getCustomerId());
		}
		if ($sellerData->getData())
		{
			$this->loadLayout();
			$this->renderLayout();
		}
		else
		{
			$this->_redirect('/');
		}
	}
}
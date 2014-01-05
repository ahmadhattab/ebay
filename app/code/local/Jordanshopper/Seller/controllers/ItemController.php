<?php
class Jordanshopper_Seller_ItemController extends Mage_Core_Controller_Front_Action
{
	public function editAction()
	{
		$this->loadLayout();
		$this->renderLayout();
	}

	public function editPostAction()
	{
		$session    = Mage::getSingleton('core/session');
		$id = $this->getRequest()->getParam('item_id');
		if (isset($id))
		{
			$sellerModel = Mage::getModel('seller/seller')->load($id);
			// get post Seller values
			$sellerId           = $this->getRequest()->getParam('seller_id');
			$productTitle       = $this->getRequest()->getParam('product_title');
			$category           = $this->getRequest()->getParam('category');
			$subtitle           = $this->getRequest()->getParam('subtitle');
			$itemConditions     = $this->getRequest()->getParam('item_conditions');
			$itemConditionsOther = $this->getRequest()->getParam('item_conditions_other');
			$itemLocation       = $this->getRequest()->getParam('item_location');
			$price              = $this->getRequest()->getParam('price');
			$qty                = $this->getRequest()->getParam('qty');
			$deliveryDetails    = $this->getRequest()->getParam('delivery_details');
			$deliveryCost 		= $this->getRequest()->getParam('delivery_cost');
			$paymentMethod      = $this->getRequest()->getParam('payment_method');
			$paypalEmail        = $this->getRequest()->getParam('paypal_email');
			$description        = $this->getRequest()->getParam('description');
			// make some validation before store these values in jordanshopper_seller
			if (isset($sellerId) && !empty($sellerId)) {
				$sellerModel->setsellerId(trim($sellerId));
			}
			if (isset($productTitle) && !empty($productTitle)) {
				$sellerModel->setproductTitle(trim($productTitle));
			}
			if ((isset($category) && !empty($category))) {
				// store all category and subcategory sapreted by a comma
				$sellerModel->setCategoriesIds(implode(",", $category));
			}
			if (isset($subtitle) && !empty($subtitle)) {
				$sellerModel->setproductSubtitle(trim($subtitle));
			}
			if (isset($itemConditions) && !empty($itemConditions)) {
				$sellerModel->setItemConditions(trim($itemConditions));
				if($itemConditions == 'other'){
					$sellerModel->setItemConditionsOther($itemConditionsOther);
				}
			}
			if (isset($itemLocation) && !empty($itemLocation)) {
				$sellerModel->setItemLocation(trim($itemLocation));
			}
			if (isset($price) && !empty($price)) {
				$sellerModel->setPrice(trim($price));
			}
			if (isset($qty) && !empty($qty)) {
				$sellerModel->setQty(trim($qty));
			}
			if (isset($deliveryDetails) && !empty($deliveryDetails)) {
				$sellerModel->setDeliveryDetails(trim($deliveryDetails));
				if($deliveryDetails == 'charge'){
					$sellerModel->setDeliveryCost($deliveryCost);
				}
			}
			if (isset($paymentMethod) && !empty($paymentMethod)) {
				$sellerModel->setPaymentMethod(trim($paymentMethod));
			}
			if (isset($paypalEmail) && !empty($paypalEmail)) {
				$sellerModel->setPaypalEmail(trim($paypalEmail));
			}
			if (isset($description) && !empty($description)) {
				$sellerModel->setDescription($description);
			}
			$sellerModel->save();
            $session->addSuccess($this->__('The item has been updated'));
            $this->_redirect('seller/index');
			
		}

	}
}
?>
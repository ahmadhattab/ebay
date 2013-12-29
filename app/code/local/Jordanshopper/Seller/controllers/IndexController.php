<?php
class Jordanshopper_Seller_IndexController extends Mage_Core_Controller_Front_Action
{
	public function indexAction()
	{
		$this->loadLayout();
		$this->renderLayout();
	}
	
	public function addAction(){
		$this->loadLayout();
		$this->renderLayout();
	}
	
	public function sellerPostAction(){
            $params       = $this->getRequest()->getParams();
            $sellerModel  = Mage::getModel('seller/seller');          			
            // get post Seller values 
            $productTitle       = $this->getRequest()->getParam('product_title');
            $category           = $this->getRequest()->getParam('category');
            $subcategory        = $this->getRequest()->getParam('subcategory');
            $subtitle           = $this->getRequest()->getParam('subtitle');
            $itemConditions     = $this->getRequest()->getParam('item_conditions');
            $itemLocation       = $this->getRequest()->getParam('item_location');
            $price              = $this->getRequest()->getParam('price');
            $qty                = $this->getRequest()->getParam('qty');
            $deliveryDetails    = $this->getRequest()->getParam('delivery_details');
            $paymentMethod      = $this->getRequest()->getParam('payment_method');
            $description        = $this->getRequest()->getParam('description');
            $paypalEmail        = $this->getRequest()->getParam('paypal_email');
            
            
            // make some validation before store these values in jordanshopper_seller
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
            $sellerModel->setCreatedAt(time());
            $sellerModel->save();
            $this->_redirect('*/*/index');

            
            
            // Move upload images to Seller Folder
            
            
	}        
}
?>
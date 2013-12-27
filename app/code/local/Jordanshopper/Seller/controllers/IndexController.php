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
            $params = $this->getRequest()->getParams();
            echo '<pre>';
            //$profile  = Mage::getModel('markavip_dataflow/profile');
            //print_r($profile->getData());
            print_r($params);
            print_r($_FILES);
            // get post Seller values 
            $productTitle       = $this->getRequest()->getParam('product_title');
            $category           = $this->getRequest()->getParam('category');
            $subcategory        = $this->getRequest()->getParam('subcategory');
            $subtitle           = $this->getRequest()->getParam('subtitle');
            $itemConditions     = $this->getRequest()->getParam('item_conditions');
            $conditionOther     = $this->getRequest()->getParam('condition-other');
            $itemLocation       = $this->getRequest()->getParam('item_location');
            $price              = $this->getRequest()->getParam('price');
            $qty                = $this->getRequest()->getParam('qty');
            $deliveryDetails    = $this->getRequest()->getParam('delivery_details');
            $paymentMethod      = $this->getRequest()->getParam('payment_method');
            $description        = $this->getRequest()->getParam('description');
            
            
            // make some validation before store these values in jordanshopper_seller
            if (isset($productTitle) && !empty($productTitle)) {
                
            }
            if ((isset($category) && !empty($category)) || (isset($subcategory) && !empty($subcategory))) {
                
            }
            if (isset($subtitle) && !empty($subtitle)) {
                
            }
            if (isset($itemConditions) && !empty($itemConditions)) {
                
            }
            if (isset($conditionOther) && !empty($conditionOther)) {
                
            }
            if (isset($itemLocation) && !empty($itemLocation)) {
                
            }
            if (isset($price) && !empty($price)) {
                
            }
            if (isset($qty) && !empty($qty)) {
                
            }
            if (isset($deliveryDetails) && !empty($deliveryDetails)) {
                
            }
            if (isset($paymentMethod) && !empty($paymentMethod)) {
                
            }
            if (isset($description) && !empty($description)) {
                
            }
            
            // Move upload images to Seller Folder
            
            
	}        
}
?>
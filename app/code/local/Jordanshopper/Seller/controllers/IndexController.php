<?php
class Jordanshopper_Seller_IndexController extends Mage_Core_Controller_Front_Action
{
	public function indexAction()
	{
		$this->loadLayout();
		$this->renderLayout();
	}
	
	public function addAction(){            
                $userId =  Mage::getSingleton('customer/session')->getCustomerId();
                if(isset($userId)){
                    Mage::register("seller_id", $userId);
                }
		$this->loadLayout();
		$this->renderLayout();
	}
	
	public function sellerPostAction(){
            $params       = $this->getRequest()->getParams();
            $sellerModel  = Mage::getModel('seller/seller'); 
            $session    = Mage::getSingleton('core/session');
            // get post Seller values 
            $sellerId           = $this->getRequest()->getParam('seller_id');
            $productTitle       = $this->getRequest()->getParam('product_title');
            $category           = $this->getRequest()->getParam('category');
            $subtitle           = $this->getRequest()->getParam('subtitle');
            $itemConditions     = $this->getRequest()->getParam('item_conditions');
            $itemLocation       = $this->getRequest()->getParam('item_location');
            $price              = $this->getRequest()->getParam('price');
            $qty                = $this->getRequest()->getParam('qty');
            $deliveryDetails    = $this->getRequest()->getParam('delivery_details');
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
            if(is_array($_FILES)){
                $uploader    = new Varien_File_Uploader('images');
                $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));                    
                $uploader->setAllowRenameFiles(true);
                $uploader->setAllowCreateFolders(true);
                $uploader->setFilesDispersion(false);                                        
                $path = Mage::getBaseDir('media') . DS . $sellerId;
                if (!is_dir($path)) {
                    mkdir($path, 0777, true);
                }
                $images = array();
                foreach ($_FILES as $image){
                    if(isset($image['name'])){
                        $images[]    = $image['name']; 
                        $fileName    = date("Y-m-j-h-i").$image['name'];
                        $move        = $uploader->save($path . DS, $fileName);
                        if(!$move){
                            $session->addError("File was not uploaded, Please Check your file and try again.");
                            $this->_redirect('*/*/add');
                            return;                        
                        }
                    }
                }                
                if($imageNames = implode(",", $images)){
                 $sellerModel->setImages($imageNames);   
                }                
            }            
            $sellerModel->setCreatedAt(time());
            $sellerModel->save();
            $session->addSuccess($this->__('The item has been saved'));
            $this->_redirect('*/*/index');            
	}        
}
?>
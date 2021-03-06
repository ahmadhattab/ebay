<?php

class Jordanshopper_Seller_IndexController extends Mage_Core_Controller_Front_Action {

	public function indexAction() {
		$customer = Mage::getSingleton('customer/session');
		if ($customer->isLoggedIn()) {
			$this->loadLayout();
			$this->renderLayout();
		} else {
			$this->_redirect('customer/account');
		}
	}

	public function activityAction() {
		$this->loadLayout();
		$this->_initLayoutMessages('customer/session');
		$this->_initLayoutMessages('catalog/session');

		$this->getLayout()->getBlock('content')->append(
		$this->getLayout()->createBlock('customer/account_dashboard')
		);
		$this->getLayout()->getBlock('head')->setTitle($this->__('My Summary'));
		$this->renderLayout();
	}

	public function addAction() {
		$session = Mage::getSingleton('core/session');
		$userId = Mage::getSingleton('customer/session')->getCustomerId();
		if (isset($userId)) {
			Mage::register("seller_id", $userId);
			$this->loadLayout();
			$this->renderLayout();
		} else {
			$this->_redirect('customer/account/login');
		}
	}

	public function sellerPostAction() {
		$customer = Mage::getSingleton('customer/session');
		$session = Mage::getSingleton('core/session');

		if ($customer->isLoggedIn()) {
			if ($this->getRequest()->isPost()) {
				$sellerModel = Mage::getModel('seller/seller');
				// get post Seller values
				$sellerId = $this->getRequest()->getParam('seller_id');
				$productTitle = $this->getRequest()->getParam('product_title');
				$category = $this->getRequest()->getParam('category');
				$subtitle = $this->getRequest()->getParam('subtitle');
				$itemConditions = $this->getRequest()->getParam('item_conditions');
				$itemConditionsOther = $this->getRequest()->getParam('item_conditions_other');
				$itemLocation = $this->getRequest()->getParam('item_location');
				$itemCity = $this->getRequest()->getParam('item_city');
				$price = $this->getRequest()->getParam('price');
				$qty = $this->getRequest()->getParam('qty');
				$deliveryDetails = $this->getRequest()->getParam('delivery_details');
				$deliveryCost = $this->getRequest()->getParam('delivery_cost');
				$paymentMethod = $this->getRequest()->getParam('payment_method');
				$paypalEmail = $this->getRequest()->getParam('paypal_email');
				$description = $this->getRequest()->getParam('description');
				$contact_me = $this->getRequest()->getParam('contact_me');

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
					if ($itemConditions == 4) {
						$sellerModel->setItemConditionsOther($itemConditionsOther);
					}
				}
				if (isset($itemLocation) && !empty($itemLocation)) {
					if ($itemLocation == 'Worldwide')
					{
						$sellerModel->setItemLocation(trim($itemLocation));
					}
					else
					{
						$sellerModel->setItemLocation(trim($itemCity));
					}
				}
				if (isset($price) && !empty($price)) {
					$sellerModel->setPrice(trim($price));
				}
				if (isset($qty) && !empty($qty)) {
					$sellerModel->setQty(trim($qty));
				}
				if (isset($deliveryDetails) && !empty($deliveryDetails)) {
					$sellerModel->setDeliveryDetails(trim($deliveryDetails));
					if ($deliveryDetails == 'charge') {
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
				if (is_array($_FILES)) {
					$sortImages = $this->reArrayFiles($_FILES);
					$images = array();
					$i = 1;
					foreach ($sortImages as $image)
					//for($i=0;$i<=count($sortImages);$i++)
					{
						if ($i <= 10 )
						{
							$uploader = new Varien_File_Uploader($image);
							$uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
							$uploader->setAllowRenameFiles(true);
							$uploader->setAllowCreateFolders(true);
							$uploader->setFilesDispersion(false);
							$path = Mage::getBaseDir('media') . DS . $sellerId;
							if (!is_dir($path)) {
								mkdir($path, 0777, true);
							}
							if (isset($image['name']) && !empty($image['name'])) {
								$images[] = $image['name'];
								$fileName = date("Y-m-j-h-i") . '-' . rand(0, 3000) . '.' . substr(strrchr($image['name'],'.'),1);
								$fileNames[] = $fileName;
								$move = $uploader->save($path . DS, $fileName);
								if (!$move) {
									$session->addError("File was not uploaded, Please Check your file and try again.");
									$this->_redirect('*/*/add');
									return;
								}
							} else {
								continue;
							}
						}
						else
						{
							break;
						}
						$i++;
					}

					if ($imageNames = implode(",", $fileNames)) {
						$sellerModel->setImages($imageNames);
					}
						
				}
				$sellerModel->setCreatedAt(time());
				$sellerModel->setStatus(0);
				$sellerModel->setContactMe($contact_me);
				$sellerModel->save();
				$session->addSuccess($this->__('The item has been saved'));
				$this->_redirect('*/*/index');
			} else {
				$session->addError($this->__('Sorry you don\'t have a permission to access it!.'));
				$this->_redirect('seller/index');
			}
		} else {
			$this->_redirect('customer/account/login');
		}
	}

	public function reArrayFiles(&$file_post) {
		$file_ary = array();
		$file_count = count($file_post['images']['name']);
		$file_keys = array_keys($file_post['images']);
		for ($i = 0; $i < $file_count; $i++) {
			foreach ($file_keys as $key) {
				$file_ary[$i][$key] = $file_post['images'][$key][$i];
			}
		}
		return $file_ary;
	}

	public function getSubCatPostAction()
	{
		if ($this->getRequest()->isPost())
		{
			$catId = $this->getRequest()->getParam('catid');
			$category = Mage::getModel('catalog/category')->load($catId);
			if($category->hasChildren())
			{
				$output = "<select id='childCategory' size='10'>";
				$subCategoriesList = $category->getChildrenCategories();
				foreach ($subCategoriesList as $subcategory)
				{
					$output .= "<option value='" . $subcategory->getId() . "'>". $subcategory->getName() . "</option>";
				}
				$output .= "</select>";
			}
			echo $output;
		}
	}

	public function getChildSubCatPostAction()
	{
		if ($this->getRequest()->isPost())
		{
			$catId = $this->getRequest()->getParam('catid');
			$category = Mage::getModel('catalog/category')->load($catId);
			if($category->hasChildren())
			{
				$subCategoriesList = $category->getChildrenCategories();
				foreach ($subCategoriesList as $subcategory)
				{
					$output .= "<option value='" . $subcategory->getId() . "'>". $subcategory->getName() . "</option>";
				}
			}
			echo $output;
		}
	}

	public function getChildChildSubCatPostAction()
	{
		if ($this->getRequest()->isPost())
		{
			$catId = $this->getRequest()->getParam('catid');
			$category = Mage::getModel('catalog/category')->load($catId);
			if($category->hasChildren())
			{
				$subCategoriesList = $category->getChildrenCategories();
				foreach ($subCategoriesList as $subcategory)
				{
					$output .= "<option value='" . $subcategory->getId() . "'>". $subcategory->getName() . "</option>";
				}
			}
			echo $output;
		}
	}

	public function getChildChildChildSubCatPostAction()
	{
		if ($this->getRequest()->isPost())
		{
			$catId = $this->getRequest()->getParam('catid');
			$category = Mage::getModel('catalog/category')->load($catId);
			if($category->hasChildren())
			{
				$subCategoriesList = $category->getChildrenCategories();
				foreach ($subCategoriesList as $subcategory)
				{
					$output .= "<option value='" . $subcategory->getId() . "'>". $subcategory->getName() . "</option>";
				}
			}
			echo $output;
		}
	}

	public function contactPostAction()
	{
		$item_id = $this->getRequest()->getParam('item_id');
		$customer = Mage::getSingleton('customer/session');
		$session = Mage::getSingleton('core/session');
		$item = Mage::getModel('catalog/product')->load($item_id);
		$seller = Mage::getModel('customer/customer')->load($item->getSellerId());
		$fromEmail = $customer->getCustomer()->getEmail();
		$fromName = $customer->getCustomer()->getName();
			
		$toEmail = $seller->getEmail();
		$toName = $seller->getName();
		$body = '<p>Customer Name: ' . $customer->getCustomer()->getName() . '</p>' .
				'<p>Customer Email: ' . $customer->getCustomer()->getEmail() . '</p>' .
				'<p>Item URL: ' . $item->getProductUrl() . '</p>'
				. '<p>Customer Note: ' . $this->getRequest()->getParam('note') . '</p>'
				;
					
				// body text
				$subject = "jordanshopper.com - " . $item->getName();
				// subject text

				try{
					$mail = new Zend_Mail();
					$mail->setFrom($fromEmail, $fromName);
					$mail->addTo($toEmail, $toName);
					$mail->setSubject($subject);
					$mail->setBodyHtml($body); // here u also use setBodyText options.
					$mail->send();
					$session->addSuccess($this->__('Your email has been sent to the seller'));
					$this->_redirectUrl($item->getProductUrl());

				}catch(Exception $e){
					echo $e->getMassage();

				}
	}

}

?>
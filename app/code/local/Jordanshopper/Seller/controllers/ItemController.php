<?php
//session_start();
class Jordanshopper_Seller_ItemController extends Mage_Core_Controller_Front_Action
{
	public function editAction()
	{
		$session = Mage::getSingleton('core/session');
		if($this->getRequest()->getParam('id'))
		{
			$item = Mage::getModel('seller/seller')->load($this->getRequest()->getParam('id'));
			if($item->getStatus() == 0)
			{
				$this->loadLayout();
				$this->renderLayout();
			}
			else
			{
				$session->addError($this->__('This item not editable right now if you have any problem with it please contact admin'));
				$this->_redirect('seller');
			}
		}
		else
		{
			$session->addError($this->__('Sorry you don\'t have a permission to access it!.'));
			$this->_redirect('seller');
		}
	}

	public function editPostAction()
	{
		$session    = Mage::getSingleton('core/session');
		$id = $this->getRequest()->getParam('item_id');
		if (isset($id) && $this->getRequest()->isPost())
		{
			$sellerModel = Mage::getModel('seller/seller')->load($id);
			// get post Seller values
			$sellerId           = $this->getRequest()->getParam('seller_id');
			$productTitle       = $this->getRequest()->getParam('product_title');
			$category           = $this->getRequest()->getParam('category');
			$subtitle           = $this->getRequest()->getParam('subtitle');
			$itemConditions     = $this->getRequest()->getParam('item_conditions');
			$itemCity = $this->getRequest()->getParam('item_city');
			$itemConditionsOther = $this->getRequest()->getParam('item_conditions_other');
			$itemLocation       = $this->getRequest()->getParam('item_location');
			$price              = $this->getRequest()->getParam('price');
			$qty                = $this->getRequest()->getParam('qty');
			$deliveryDetails    = $this->getRequest()->getParam('delivery_details');
			$deliveryCost 		= $this->getRequest()->getParam('delivery_cost');
			$paymentMethod      = $this->getRequest()->getParam('payment_method');
			$paypalEmail        = $this->getRequest()->getParam('paypal_email');
			$description        = $this->getRequest()->getParam('description');
			$contact_me			= $this->getRequest()->getParam('contact-me');
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
				if($itemConditions == 4){
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
			$originImages = $sellerModel->getImages();
			$arr_of_origin_images = explode(",", $originImages);
			if ($_FILES['images']['name'][0])
			{
				if (count($arr_of_origin_images) <= 10)
				{
					if (is_array($_FILES)) {
						$sortImages = $this->reArrayFiles($_FILES);
						$images = array();
						foreach ($sortImages as $image) {
							$uploader = new Varien_File_Uploader($image);
							$uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
							$uploader->setAllowRenameFiles(true);
							$uploader->setAllowCreateFolders(true);
							$uploader->setFilesDispersion(false);
							$path = Mage::getBaseDir('media') . DS . $sellerModel->getSellerId();
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
									//$this->_redirect('*/*/add');
									return;
								}
							} else {
								continue;
							}
						}
						if ($imageNames = implode(",", $fileNames)) {
							$allImages = $imageNames.",".$originImages;
							$sellerModel->setImages($allImages);
						}
					}
				}
			}

			if ($this->getRequest()->getParam('delimg'))
			{
				$del_imgs = $this->getRequest()->getParam('delimg');
				foreach($del_imgs as $del_img)
				{
					$originImages = $sellerModel->getImages();
					$originImages = explode(",", $originImages);
					unset($originImages[array_search($del_img, $originImages)]);
					$all_images = implode(",", $originImages);
					//$all_images = str_replace($del_img . ',', "", $originImages);
					$sellerModel->setImages($all_images);
					$path = Mage::getBaseDir('media') . DS . $sellerModel->getSellerId() . DS . $del_img;
					unlink($path);
				}
			}

			$sellerModel->setContactMe($contact_me);
			$sellerModel->save();
			$session->addSuccess($this->__('The item has been updated'));
			$this->_redirect('seller/index');

		} else {
			$session->addError($this->__('Sorry you don\'t have a permission to access it!.'));
			$this->_redirect('seller');
		}

	}

	public function submitAction()
	{
		$session    = Mage::getSingleton('core/session');
		if ($this->getRequest()->getParam('id'))
		{
			$item = Mage::getModel('seller/seller')->load($this->getRequest()->getParam('id'));
			if($item->getStatus() == 0)
			{
				$this->loadLayout();
				$this->renderLayout();
			}
			elseif($item->getStatus() == 1)
			{
				$session->addError($this->__('This item already submitted if you have any problem with it please contact admin'));
				$this->_redirect('seller');
			}

		}
		else
		{
			$session->addError($this->__('Sorry you don\'t have a permission to access it!.'));
			$this->_redirect('seller');
		}
	}
	public function paidsubmitAction()
	{
		$session    = Mage::getSingleton('core/session');
		if ($this->getRequest()->getParam('id'))
		{
			$item = Mage::getModel('seller/seller')->load($this->getRequest()->getParam('id'));
			if($item->getStatus() == 0 && $item->getData())
			{
				$this->loadLayout();
				$this->renderLayout();
			}
			elseif($item->getStatus() == 1)
			{
				$session->addError($this->__('This item already submitted if you have any problem with it please contact admin'));
				$this->_redirect('seller');
			}
			else
			{
				$session->addError($this->__('There\'s no item related'));
				$this->_redirect('seller');
			}

		}
		else
		{
			$session->addError($this->__('Sorry you don\'t have a permission to access it!.'));
			$this->_redirect('seller');
		}
	}

	public function freesubmitAction()
	{
		$session    = Mage::getSingleton('core/session');
		//$code = $this->getRequest()->getParam('discountCode');

		$seller_collection = Mage::getModel('catalog/product')->getCollection()
		->addAttributeToFilter('seller_id', array('eq' => Mage::getSingleton('customer/session')->getCustomer()->getId()))
		->addAttributeToFilter('created_at', array('gteq' => date('Y-m-d H:i:s')));
			
		if ($this->getRequest()->isPost() && ($this->getRequest()->getParam('form_key') == Mage::getSingleton('core/session')->getFormKey()) && ($seller_collection->count() <= 10))
		{
			$itemId = $this->getRequest()->getParam('seller_id');
			$item = Mage::getModel('seller/seller')->load($itemId);
			if($item->getStatus() == 0 && $item->getData())
			{
				$itemModel = Mage::getModel('seller/seller');
				$start_date = date('Y-m-d');
				$end_date = strtotime(date("Y-m-d", strtotime($start_date)) . " +30 days");
				$itemModel->createProduct($itemId, $start_date, $end_date);
				$successMsg = $this->__('Your order has been received <a href="%s">click here</a> to view it', Mage::getUrl('sales/order/view', array('order_id' => $orderNumber)));
				$session->addSuccess($successMsg);
				$this->_redirect('seller');

			}
			elseif($item->getStatus() == 1)
			{
				$session->addError($this->__('This item already submitted if you have any problem with it please contact admin'));
				$this->_redirect('seller');
			}
			else
			{
				$session->addError($this->__('There\'s no item related'));
				$this->_redirect('seller');
			}
		}
		else
		{
			$error = 'this request not allowed';
			$session->addError($error);
			$this->_redirect('seller');
		}
	}

	public function freesubmitcodeAction()
	{
		$session    = Mage::getSingleton('core/session');
		$code = $this->getRequest()->getParam('discountCode');
		if ($code == 'sell_now')
		{
			$itemId = $this->getRequest()->getParam('seller_id');
			$item = Mage::getModel('seller/seller')->load($itemId);
			if($item->getStatus() == 0 && $item->getData())
			{
				$itemModel = Mage::getModel('seller/seller');
				$start_date = date('Y-m-d');
				$end_date = strtotime(date("Y-m-d", strtotime($start_date)) . " +30 days");
				$itemModel->createProduct($itemId, $start_date, $end_date);
				$successMsg = $this->__('Your order has been received <a href="%s">click here</a> to view it', Mage::getUrl('sales/order/view', array('order_id' => $orderNumber)));
				$session->addSuccess($successMsg);
				$this->_redirect('seller');
			}
			elseif($item->getStatus() == 1)
			{
				$session->addError($this->__('This item already submitted if you have any problem with it please contact admin'));
				$this->_redirect('seller');
			}
			else
			{
				$session->addError($this->__('There\'s no item related'));
				$this->_redirect('seller');
			}
		}
		else
		{
			$error = 'This discount code not valid';
			$session->addError($error);
			$this->_redirect('seller');
		}
	}

	public function checkoutAction()
	{
		$session = Mage::getSingleton('core/session');
		//Mage::getSingleton('customer/session')->getCustomerId();
		//die();
		$PayPalMode 			= ''; // sandbox or live
		$PayPalApiUsername 		= 'info_api1.jordanshopper.com'; //PayPal API Username
		$PayPalApiPassword 		= 'HN62SUAEJZJ2TLGY'; //Paypal API password
		$PayPalApiSignature 	= 'Axzk9000mIaVNrIpQRZmdax5QRIJA6O1Mla0DupjdJCntcfP20FF-EzX'; //Paypal API Signature
		$PayPalCurrencyCode 	= 'USD'; //Paypal Currency Code
		$PayPalReturnURL 		= Mage::getUrl('seller/item/checkout'); //Point to process.php page
		$PayPalCancelURL 		= Mage::getUrl('seller/index');//Cancel URL if user clicks cancel
		$PayPalModel = Mage::getModel('seller/seller');
		if ($this->getRequest()->isPost())
		{
			//Mainly we need 4 variables from an item, Item Name, Item Price, Item Number and Item Quantity.
			$ItemName = $this->getRequest()->getParam("itemname"); //Item Name
			$ItemPrice = $this->getRequest()->getParam("itemprice"); //Item Price
			$ItemNumber = $this->getRequest()->getParam("itemnumber"); //Item Number
			$ItemQty = $this->getRequest()->getParam("itemQty"); // Item Quantity
			$ItemTotalPrice = ($ItemPrice * $ItemQty); //(Item Price x Quantity = Total) Get total amount of product;

			//Data to be sent to paypal
			$padata = 	'&CURRENCYCODE='.urlencode($PayPalCurrencyCode).
			'&PAYMENTACTION=Sale'.
			'&ALLOWNOTE=1'.
			'&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($PayPalCurrencyCode).
			'&PAYMENTREQUEST_0_AMT='.urlencode($ItemTotalPrice).
			'&PAYMENTREQUEST_0_ITEMAMT='.urlencode($ItemTotalPrice).
			'&L_PAYMENTREQUEST_0_QTY0='. urlencode($ItemQty).
			'&L_PAYMENTREQUEST_0_AMT0='.urlencode($ItemPrice).
			'&L_PAYMENTREQUEST_0_NAME0='.urlencode($ItemName).
			'&L_PAYMENTREQUEST_0_NUMBER0='.urlencode($ItemNumber).
			'&AMT='.urlencode($ItemTotalPrice).
			'&RETURNURL='.urlencode($PayPalReturnURL ).
			'&CANCELURL='.urlencode($PayPalCancelURL);

			//We need to execute the "SetExpressCheckOut" method to obtain paypal token
			$httpParsedResponseAr = $PayPalModel->PPHttpPost('SetExpressCheckout', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
			if (!$httpParsedResponseAr)
			{
				$this->_redirect('seller');
			}
			//Respond according to message we receive from Paypal
			if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"]))
			{
					
				// If successful set some session variable we need later when user is redirected back to page from paypal.
				$session->setItemPrice($ItemPrice);
				$session->setTotalAmount($ItemTotalPrice);
				$session->setItemName(ItemName);
				$session->setItemNo($ItemNumber);
				$session->setProductID($this->getRequest()->getParam('productID'));
				$session->setItemQTY($ItemQty);

				if($PayPalMode=='sandbox')
				{
					$paypalmode 	=	'.sandbox';
				}
				else
				{
					$paypalmode 	=	'';
				}
				//Redirect user to PayPal store with Token received.
				$paypalurl ='https://www'.$paypalmode.'.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.$httpParsedResponseAr["TOKEN"].'';
				$this->_redirectUrl($paypalurl);
			}else{
				//Show error message
				$session->addError('PayPal: ' . urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]));
				$this->_redirect('seller');
				//exit();
			}

		}

		//Paypal redirects back to this page using ReturnURL, We should receive TOKEN and Payer ID
		if(isset($_GET["token"]) && isset($_GET["PayerID"]))
		{
			//we will be using these two variables to execute the "DoExpressCheckoutPayment"
			//Note: we haven't received any payment yet.

			$token = $_GET["token"];
			$playerid = $_GET["PayerID"];

			//get session variables
			$ItemPrice 		= $session->getItemPrice();
			$ItemTotalPrice = $session->getTotalAmount();
			$ItemName 		= $session->getItemName();
			$ItemNumber 	= $session->getItemNo();
			$ItemQTY 		= $session->getItemQTY();
			$padata = 	'&TOKEN='.urlencode($token).
			'&PAYERID='.urlencode($playerid).
			'&PAYMENTACTION='.urlencode("SALE").
			'&AMT='.urlencode($ItemTotalPrice).
			'&CURRENCYCODE='.urlencode($PayPalCurrencyCode);

			//We need to execute the "DoExpressCheckoutPayment" at this point to Receive payment from user.
			$httpParsedResponseAr = $PayPalModel->PPHttpPost('DoExpressCheckoutPayment', $padata, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
			//Check if everything went ok..
			if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"]))
			{
				//Generate Seller Item here
				$item = Mage::getModel('catalog/product')->load($ItemNumber);
				$customerId = Mage::getSingleton('customer/session')->getCustomerId();
				$sku = $item->getSku();
				$orderNumber = $PayPalModel->createOrder($customerId, $sku);
				$successMsg = $this->__('Your order has been received <a href="%s">click here</a> to view it', Mage::getUrl('sales/order/view', array('order_id' => $orderNumber)));
				$session->addSuccess($successMsg);
				// create live product
				$start_date = date('Y-m-d');
				$end_date = strtotime(date("Y-m-d", strtotime($start_date)) . " +" . $item->getPeriodDays() . " days");
				$PayPalModel->createProduct($session->getProductID(), $start_date, $end_date);
				$this->_redirect('seller/index');
			}
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

	public function reportPostAction()
	{
		if ($this->getRequest()->isPost())
		{
			$data = $this->getRequest()->getParams();
			$session = Mage::getSingleton('core/session');
			$session->addSuccess($this->__('Thank you for your reporting'));
			$this->_redirectUrl($_SERVER['HTTP_REFERER']);

			$mail = Mage::getModel('core/email');
			$mail->setToName('JordnShopper');
			$mail->setToEmail('info@jordanshopper.com');
			$mail->setBody('
					<p><strong>Type:</strong> ' . $data['report-type'] . '</p>
					<p><strong>Note:</strong> ' . $data['note'] . '</p>
					<p><strong>Product URL:</strong> ' . $_SERVER['HTTP_REFERER'] . '</p>
					');
			$mail->setSubject('Listing Report');
			$mail->setFromEmail(Mage::getSingleton('customer/session')->getCustomer()->getEmail());
			$mail->setFromName(Mage::getSingleton('customer/session')->getCustomer()->getName());
			$mail->setType('html');// YOu can use Html or text as Mail format
			$mail->send();
		}
		else
		{
			$this->_redirect('/');
		}
	}
}
?>

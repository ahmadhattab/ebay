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

	public function checkoutAction()
	{
		$session = Mage::getSingleton('core/session');
	    //Mage::getSingleton('customer/session')->getCustomerId();
		//die();
		$PayPalMode 			= 'sandbox'; // sandbox or live
		$PayPalApiUsername 		= 'ahmad_hatab_api1.hotmail.com'; //PayPal API Username
		$PayPalApiPassword 		= '1389030811'; //Paypal API password
		$PayPalApiSignature 	= 'AIbAIH0FvfS.PgrpnVaXfekniVeDATstQkrZXwyNL8IZJ7sVo5IUXokc'; //Paypal API Signature
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
				$PayPalModel->createProduct($session->getProductID());
				$this->_redirect('seller/index');
			}
		}


	}
}
?>
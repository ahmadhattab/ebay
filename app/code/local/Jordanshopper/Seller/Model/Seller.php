<?php
class Jordanshopper_Seller_Model_Seller extends Mage_Core_Model_Abstract{
	public function _construct()
	{
		parent::_construct();
		$this->_init('seller/seller');
	}

	public function PPHttpPost($methodName_, $nvpStr_, $PayPalApiUsername, $PayPalApiPassword, $PayPalApiSignature, $PayPalMode) {
		$session = Mage::getSingleton('core/session');
		// Set up your API credentials, PayPal end point, and API version.
		$API_UserName = urlencode($PayPalApiUsername);
		$API_Password = urlencode($PayPalApiPassword);
		$API_Signature = urlencode($PayPalApiSignature);

		if($PayPalMode=='sandbox')
		{
			$paypalmode 	=	'.sandbox';
		}
		else
		{
			$paypalmode 	=	'';
		}

		$API_Endpoint = "https://api-3t".$paypalmode.".paypal.com/nvp";
		$version = urlencode('76.0');

		// Set the curl parameters.
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);

		// Turn off the server and peer verification (TrustManager Concept).
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);

		// Set the API operation, version, and API signature in the request.
		$nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";

		// Set the request as a POST FIELD for curl.
		curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

		// Get response from the server.
		$httpResponse = curl_exec($ch);

		if(!$httpResponse) {
			$session->addError("$methodName_ failed: ".curl_error($ch).'('.curl_errno($ch).')');
			return false;
		}

		// Extract the response details.
		$httpResponseAr = explode("&", $httpResponse);

		$httpParsedResponseAr = array();
		foreach ($httpResponseAr as $i => $value) {
			$tmpAr = explode("=", $value);
			if(sizeof($tmpAr) > 1) {
				$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
			}
		}

		if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
			exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
		}

		return $httpParsedResponseAr;
	}

	public function createOrder($customerId, $sku, $qty=1)
	{
		//create a cart
		$quote = Mage::getModel('sales/quote')
		->setStoreId(Mage::app()->getStore()->getId());


		//Get Customer by Id
		$customer = Mage::getModel('customer/customer')->load($customerId);

		//attach customer to cart
		$quote->assignCustomer($customer);

		//attach products
		//foreach ($_POST as $sku=>$qty)
		//{

		$product =     Mage::helper('catalog/product')->getProduct($sku, Mage::app()->getStore()->getId(), 'sku');
		$buyInfo = array(
        'qty' => $qty,
		// custom option id => value id
		// or
		// configurable attribute id => value id
		);
		$quote->addProduct($product, new Varien_Object($buyInfo));

		//}
		//get and set customer billing address
		//need to work on this encase we use diffrent billing and shipping addresses
		$addressData = Mage::getModel('customer/address')->load(1);


		$billingAddress = $quote->getBillingAddress()->addData($addressData);
		$shippingAddress = $quote->getShippingAddress()->addData($addressData);

		// set shipping and payment methods. assumes freeshipping and check payment
		// have been enabled.
		/*
		$shippingAddress->setCollectShippingRates(true)->collectShippingRates()
		->setShippingMethod('freeshipping_freeshipping')
		->setPaymentMethod('checkmo');
		*/

		// THIS IS WHERE THE ERROR SEEMS TO BE
		$quote->getShippingAddress()->setShippingMethod('freeshipping_freeshipping');
		//$quote->getShippingAddress()->setCollectShippingRates(true);
		//$quote->getShippingAddress()->collectShippingRates();

		//set payment method
		$quote->getPayment()->importData(array('method' => 'seller'));


		//save cart and check out
		$quote->collectTotals()->save();


		$service = Mage::getModel('sales/service_quote', $quote);
		$service->submitAll();
		$order = $service->getOrder();
		$newOrder = Mage::getModel('sales/order')->load($order->getId());
		$newOrder->sendNewOrderEmail();
		Mage::getSingleton('checkout/session')->clear();
		Mage::getSingleton('checkout/cart')->truncate();
		$cart = Mage::getSingleton('checkout/session')->getQuote();
		$cart->delete();
		return $order->getIncrementId();
	}

	public function createProduct($itemId)
	{
		// this will save item in eav model
		$product = Mage::getModel('catalog/product');
		// in this step we will add our custom fields to
		$product->setItemConditions();
	}
}
?>
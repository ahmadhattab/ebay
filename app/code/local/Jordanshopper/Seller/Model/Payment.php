<?php 
class Jordanshopper_Seller_Model_Payment extends Mage_Payment_Model_Method_Abstract
{
	protected $_code = 'seller';
	protected $_formBlockType = 'seller/form';
	protected $_isInitializeNeeded      = true;
	protected $_canUseInternal          = false;
	protected $_canUseForMultishipping  = false;
	
}
?>
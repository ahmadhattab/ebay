<?php
class Jordanshopper_Seller_Model_Feedback extends Mage_Core_Model_Abstract{
	public function _construct()
	{
		parent::_construct();
		$this->_init('seller/feedback');
	}
}
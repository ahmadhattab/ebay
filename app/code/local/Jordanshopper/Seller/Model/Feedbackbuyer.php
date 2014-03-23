<?php
class Jordanshopper_Seller_Model_Feedbackbuyer extends Mage_Core_Model_Abstract{
	public function _construct()
	{
		parent::_construct();
		$this->_init('seller/feedbackbuyer');
	}
}
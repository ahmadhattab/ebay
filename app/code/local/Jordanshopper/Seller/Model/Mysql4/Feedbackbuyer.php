<?php
class Jordanshopper_Seller_Model_Mysql4_Feedbackbuyer extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('seller/feedbackbuyer', 'id');
    }

}
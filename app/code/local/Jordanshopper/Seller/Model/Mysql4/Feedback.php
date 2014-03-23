<?php
class Jordanshopper_Seller_Model_Mysql4_Feedback extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('seller/feedback', 'id');
    }

}
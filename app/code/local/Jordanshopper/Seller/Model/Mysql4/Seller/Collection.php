<?php
/**
 * Data collection for Seller 
 *
 * @author Salameh Yasin <salameh.yaseen@gmail.com> 
 */
class Jordanshopper_Seller_Model_Mysql4_Seller_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
    	parent::_construct();
        $this->_init('seller/seller');
    }
}

<?php
/**
 * Seller Model
 * 
 * @package Jordanshopper_Seller
 * @author Salameh Yasin <salameh.yaseen@gmail.com> 
 */
class Jordanshopper_Seller_Model_Seller extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('jordanshopper_seller/seller');
    }

}
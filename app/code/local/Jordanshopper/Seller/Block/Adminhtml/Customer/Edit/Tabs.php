<?php

class Jordanshopper_Seller_Block_Adminhtml_Customer_Edit_Tabs 
      extends Mage_Adminhtml_Block_Customer_Edit_Tabs
{
 
    protected function _beforeToHtml()
    {
        $this->addTabAfter('product', array(
                'label'     => Mage::helper('customer')->__('Product List'),
        		'title'		=> Mage::helper('customer')->__('Product List'),
                'content'   => $this->getLayout()->createBlock('seller/adminhtml_customer_edit_listing')->toHtml(),
            ),'tags');
        parent::_beforeToHtml();
    }
}

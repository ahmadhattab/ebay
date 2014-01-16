<?php

class Jordanshopper_Seller_Block_Adminhtml_Customer_Edit_Tabs 
      extends Mage_Adminhtml_Block_Customer_Edit_Tabs
{
 
    protected function _beforeToHtml()
    {
        $this->addTabAfter('product', array(
                'label'     => Mage::helper('customer')->__('Product List'),
                //'content'   => $this->getLayout()->createBlock('jordanshopper_seller/adminhtml_customer_edit_listing')->initForm()->toHtml(),
                'url'       => $this->getUrl('*/*/productList', array('_current' => true)),
                'class'     => 'ajax',
            ),'tags');
        parent::_beforeToHtml();
    }
}

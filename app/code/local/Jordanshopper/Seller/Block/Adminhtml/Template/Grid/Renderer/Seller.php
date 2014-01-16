<?php

class Jordanshopper_Seller_Block_Adminhtml_Template_Grid_Renderer_Seller 
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
   public function render(Varien_Object $row)
    {       
       $customerId = $row->seller_id;
       if(isset($customerId) && !empty($customerId)){
           $customer = Mage::getModel('customer/customer')
            ->load($customerId); 
           // generate url for edit customer
           $url =  Mage::helper("adminhtml")->getUrl("adminhtml/customer/edit",array('id'=>$customerId));
           $output = "<a target='_blank' href='$url'>{$customer->getName()}</a>";
           return  $output;
       }
       
       
    }
    
} 


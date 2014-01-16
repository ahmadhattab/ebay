<?php

class Jordanshopper_Seller_Block_Adminhtml_Catalog_Product_Grid extends Mage_Adminhtml_Block_Catalog_Product_Grid
{
    protected function _prepareCollection()
    {    
       $collection = Mage::getModel('catalog/product')->getCollection()
                     ->addAttributeToSelect('seller_id','seller_id');                     
       parent::_prepareCollection();
       $this->setCollection($collection);
       // When i put setCollection() will not bring the values which is not in the parent method 
       // So plzz find it and tell me what ahppens 
    }
    
    protected function _prepareColumns()
    {
        $this->addColumnAfter('seller',
            array(
                'header'=> Mage::helper('catalog')->__('Seller'),
                'width' => '150px',
                'type'  => 'text',
                'index' => 'seller_id',
                'renderer' => 'Jordanshopper_Seller_Block_Adminhtml_Template_Grid_Renderer_Seller'
                ),'name');

        return parent::_prepareColumns();
    }
}
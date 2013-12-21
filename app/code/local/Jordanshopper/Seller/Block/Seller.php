<?php 
class Jordanshopper_Seller_Block_Seller extends Mage_Core_Block_Template
{
	public function getSeller()
	{
		return 'Hello Sellerrrr';
	}
	public function getCategoryTree()
    	{        
	        // make it as parent an childs 
                $object = new Mage_Catalog_Block_Navigation();
	        $actualCategoryId = $object->getCurrentCategory()->getId();        
	        $actualCategory = Mage::getModel('catalog/category')->load($actualCategoryId);
	        $subCategories = explode(',', $actualCategory->getChildren());        
	        $output = "<ul>";
	        foreach ($subCategories as $subCategoryId) {
	            $category = Mage::getModel('catalog/category')->load($subCategoryId);                 
	           if ($category->getIsActive()) {
	                $output .= "<li><input type='checkbox' value='{$category->getId()}'>{$category->getName()}";                               
	           }
	            if($category->hasChildren()){                
	                $output .= "<ul>";
	                $subCategoriesList = $category->getChildrenCategories();
	                foreach ($subCategoriesList as $subcategory){
	                    $output .= "<li><input type='checkbox' value='{$subcategory->getId()}'>{$subcategory->getName()}</li>";                               
	                }
                            $output .= "</ul></li>";
                    }else{
                        $output .= "</li>";
                    }
	        }
                    $output .= "</ul>";
                    return $output;
    	}

}
?>

<?php 
class Jordanshopper_Seller_Block_Seller extends Mage_Core_Block_Template
{
	public function getSeller()
	{
		return 'Hello Sellerrrr';
	}
	public function getCategoryTree()
    	{        
	        $object = new Mage_Catalog_Block_Navigation();
	        $actualCategoryId = $object->getCurrentCategory()->getId();        
	        $actualCategory = Mage::getModel('catalog/category')->load($actualCategoryId);
	        $subCategories = explode(',', $actualCategory->getChildren());        
	        echo "<ul>";
	        foreach ($subCategories as $subCategoryId) {
	            $category = Mage::getModel('catalog/category')->load($subCategoryId);                 
	           if ($category->getIsActive()) {
	                echo "<li><input type='checkbox' value='{$category->getId()}'>{$category->getName()}</li>";                               
	           }
	            if($category->hasChildren()){                
	                echo "<li><ul>";
	                $subCategoriesList = $category->getChildrenCategories();
	                foreach ($subCategoriesList as $subcategory){
	                    echo "<li><input type='checkbox' value='{$subcategory->getId()}'>{$subcategory->getName()}</li>";                               
	                }
	                echo "</ul></li>";
	            }
	        }
        	echo "</ul>";
    	}

}
?>

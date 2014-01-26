<?php 
class Jordanshopper_Seller_Block_Seller extends Mage_Core_Block_Template
{
	public function getSeller()
	{
		return Mage::getSingleton('admin/session')->getUser();
	}
	public function getCategoryTree()
    	{        
	        // make it as parent an childs 
                $object = new Mage_Catalog_Block_Navigation();
	        $actualCategoryId = $object->getCurrentCategory()->getId();        
	        $actualCategory = Mage::getModel('catalog/category')->load($actualCategoryId);
	        $subCategories = explode(',', $actualCategory->getChildren());        
	        $output = "<ul class='x-tree-node-ct'>";
	        foreach ($subCategories as $subCategoryId) {
	        	if ($subCategoryId == 5){ continue;}
	            $category = Mage::getModel('catalog/category')->load($subCategoryId);                 
	           if ($category->getIsActive()) {
	                $output .= "<li class='x-tree-node'><input type='checkbox' name='category[]' class=\"validate-one-required-by-name seller-category\" value='{$category->getId()}'><p>{$category->getName()}</p>";                               
	           }
	            if($category->hasChildren()){                
	                $output .= "<ul>";
	                $subCategoriesList = $category->getChildrenCategories();
	                foreach ($subCategoriesList as $subcategory){
	                    $output .= "<li class='menu-item-2'><input type='checkbox' name='category[]' class=\"validate-one-required-by-name seller-category\" value='{$subcategory->getId()}'><p>{$subcategory->getName()}</p></li>";                               
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

<?php
class Jordanshopper_Seller_Block_Seller_Item extends Mage_Core_Block_Template
{
	public function getItem()
	{
		$id = $this->getRequest()->getParam('id');
		$item = Mage::getModel('seller/seller')->load($id);
		return $item;
	}
	public function getCategoryTree()
	{
		// make it as parent an childs
		$item = $this->getItem();
		$catIds = $item->getCategories_ids();
		$catIds = explode(",", $catIds);
		$object = new Mage_Catalog_Block_Navigation();
		$actualCategoryId = $object->getCurrentCategory()->getId();
		$actualCategory = Mage::getModel('catalog/category')->load($actualCategoryId);
		$subCategories = explode(',', $actualCategory->getChildren());
		$output = "<ul class='x-tree-node-ct'>";
		foreach ($subCategories as $subCategoryId) {
		if ($subCategoryId == 5){ continue;}
			$category = Mage::getModel('catalog/category')->load($subCategoryId);
			if ($category->getIsActive()) 
			{
				$output .= "<li class='x-tree-node'><input type='checkbox' ";

				if (in_array($category->getId(), $catIds))
				{
					$output .= "checked=checked";	
				}
				$output .= " name='category[]' value='{$category->getId()}'>{$category->getName()}";
			}
			if($category->hasChildren())
			{
				$output .= "<ul>";
				$subCategoriesList = $category->getChildrenCategories();
				foreach ($subCategoriesList as $subcategory)
				{
					$output .= "<li class='menu-item-2'><input type='checkbox' ";

					if (in_array($subcategory->getId(), $catIds))
					{
					$output .= "checked=checked";
					}
					$output .= " name='category[]' value='{$subcategory->getId()}'>{$subcategory->getName()}</li>";
				}
				$output .= "</ul></li>";
			}else
			{
				$output .= "</li>";
			}
		}
		$output .= "</ul>";
		return $output;
	}
	
	public function getListingItems()
	{
		$categoryId = 5;
		$category = Mage::getModel('catalog/category');
		$category->load($categoryId);
		$collection = $category->getProductCollection();
		$collection->addAttributeToSelect('*');
		return $collection;
	}
}
?>
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
		$object = new Mage_Catalog_Block_Navigation();
		$actualCategoryId = $object->getCurrentCategory()->getId();
		$actualCategory = Mage::getModel('catalog/category')->load($actualCategoryId);
		$subCategories = explode(',', $actualCategory->getChildren());
		$output = "<select class='validate-select required-entry' id='parentCategory' size='10' name='category[]'>";
		foreach ($subCategories as $subCategoryId)
		{
			$category = Mage::getModel('catalog/category')->load($subCategoryId);
			if ($category->getIsActive())
			{
				$output .= "<option value='" . $category->getId() . "'>". $category->getName() . "</option>";
			}
		}
		$output .= '</select>';
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
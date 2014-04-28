<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Catalog
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * Catalog navigation
 *
 * @category   Mage
 * @package    Mage_Catalog
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Jordanshopper_Buyer_Block_Navigation extends Mage_Core_Block_Template
{
	public function renderCategoriesMenuHtml()
	{
		$object = new Mage_Catalog_Block_Navigation();
		$actualCategoryId = $object->getCurrentCategory()->getId();
		$actualCategory = Mage::getModel('catalog/category')->load($actualCategoryId);
		$subCategories = explode(',', $actualCategory->getChildren());
		
		$categoryGroups = array_chunk($subCategories, (int)ceil(count($subCategories)/3));

		$output .= '<table border="0" cellpadding="0" cellspacing="0" width="100%" xmlns="http://www.w3.org/1999/xhtml"><tbody><tr>';
		$i = 0;
			foreach ($categoryGroups as $categoryGroup)
			{
					$output .= '<td';
					if ($i ==0 )
						$output .=' style="width: 320px">';
					else 
						$output .= '>';
					foreach ($categoryGroup as $subCategoryId)
					{
						$category = Mage::getModel('catalog/category')->load($subCategoryId);
						if ($category->getIsActive())
						{
							$output .= '<table><tbody>';
							$output .= '<tr><td class="parent"><b><a href="' . $category->getUrl() . '">' . $category->getName() . '</a></b></td></tr>';
							if($category->hasChildren())
							{
								$childs = explode(',', $category->getChildren());
								foreach ($childs as $child)
								{
									$subCategory = Mage::getModel('catalog/category')->load($child);
									$output .= '<tr><td class="child"><a href="' . $subCategory->getUrl() . '">' . $subCategory->getName() . '</a>';
								}
							}
							$output .= '</table>';
							
						}
						
					}
					$output .= '</td>';
					$i++;
			}
		$output .= '</tr></tbody></table>';
		/*
		foreach ($subCategories as $subCategoryId)
		{
			$category = Mage::getModel('catalog/category')->load($subCategoryId);
			if ($category->getIsActive())
			{

				$output .= '<td>';
				
				$output .= '<table><tbody>';
				$output .= '<tr><td><a href="' . $category->getUrl() . '">' . $category->getName() . '</a></td></tr>';
				if($category->hasChildren())
				{
					$childs = explode(',', $category->getChildren());
					foreach ($childs as $child)
					{
						$subCategory = Mage::getModel('catalog/category')->load($child);
						$output .= '<tr><td><a href="' . $subCategory->getUrl() . '">' . $subCategory->getName() . '</a>';
					}
				}
				$output .= '</table>';

				$output .= '</td>';

			}
			$i++;
		}
		$output .= '</tr></tbody></table>';
		*/
		return $output;
	}
}

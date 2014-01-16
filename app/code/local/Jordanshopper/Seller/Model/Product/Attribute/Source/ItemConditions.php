<?php
class Jordanshopper_Seller_Model_Product_Attribute_Source_ItemConditions extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
	public function getAllOptions()
	{
		if (!$this->_options)
		{
			$this->_options = array(
			array(
					'value' => '',
					'label' => '',
			),
			array(
					'value' => 'new',
					'label' => 'New',
			),
			array(
					'value' => 'used',
					'label' => 'Used',
			),
			array(
					'value' => 'other',
					'label' => 'Other',
			)
			);
		}
		return $this->_options;
	}

}
?>
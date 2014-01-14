<?php
class Jordanshopper_Seller_Model_Product_Attribute_Source_DeliveryDetails extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
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
					'value' => 'none',
					'label' => 'None',
			),
			array(
					'value' => 'free',
					'label' => 'Free',
			),
			array(
					'value' => 'charge',
					'label' => 'Charge',
			)
			);
		}
		return $this->_options;
	}
}
?>
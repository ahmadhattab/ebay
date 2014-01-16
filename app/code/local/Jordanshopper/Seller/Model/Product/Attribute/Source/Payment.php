<?php 
class Jordanshopper_Seller_Model_Product_Attribute_Source_Payment extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
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
					'value' => '1',
					'label' => 'Cash On Delivery',
				),
				array(
					'value' => '2',
					'label' => 'PayPal',
				)
			);
		}
		return $this->_options;
	}
}
?>
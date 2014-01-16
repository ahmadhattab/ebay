<?php
$this->startSetup();


$this->run("
    ALTER TABLE `jordanshopper_seller`
        ADD COLUMN `delivery_cost` VARCHAR(255) NOT NULL AFTER `delivery_details`,
        ADD COLUMN `item_conditions_other` VARCHAR(255) NOT NULL AFTER `item_conditions`,
        DROP COLUMN `warrenty`,
        DROP COLUMN `retains`
");

$this->endSetup();

?>
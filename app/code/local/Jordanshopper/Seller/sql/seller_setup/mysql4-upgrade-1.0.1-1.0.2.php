<?php
$this->startSetup();


$this->run("
    ALTER TABLE `jordanshopper_seller`
        ADD COLUMN `item_location` VARCHAR(255) NOT NULL AFTER `item_conditions`
");

$this->endSetup();

?>
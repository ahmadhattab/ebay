<?php
$this->startSetup();


$this->run("
    ALTER TABLE `jordanshopper_seller`
        ADD COLUMN `contact_me` int(1) NOT NULL AFTER `delivery_cost`
");

$this->endSetup();

?>
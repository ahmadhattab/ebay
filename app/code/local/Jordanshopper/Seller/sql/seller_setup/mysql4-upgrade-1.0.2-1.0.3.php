<?php
$this->startSetup();


$this->run("
    ALTER TABLE `jordanshopper_seller`
        ADD COLUMN `product_subtitle` VARCHAR(255) NOT NULL AFTER `product_title`
");

$this->endSetup();

?>
<?php
$this->startSetup();


$this->run("
    ALTER TABLE `jordanshopper_seller`
        ADD COLUMN `live_id` int(11) NOT NULL AFTER `created_at`");

$this->endSetup();

?>
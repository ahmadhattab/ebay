<?php
$this->startSetup();
$this->run("
        DROP TABLE IF EXISTS `{$this->getTable('seller/feedbackseller')}`;
         CREATE TABLE `{$this->getTable('seller/feedbackseller')}` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `seller_id` int(11) NOT NULL,
            `order_number` varchar(255) NOT NULL,
            `feedback` int(1) NOT NULL,
            `item_desc` int(1) NOT NULL,
            `price` int(1) NOT NULL,
            `ship` int(1) NOT NULL,
            `communication` int(1) NOT NULL,
            `text` varchar(255) NOT NULL,
            PRIMARY KEY (`id`)           
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8
");
    
$this->endSetup();
<?php
$this->startSetup();
$this->run("
        DROP TABLE IF EXISTS `{$this->getTable('seller/feedbackbuyer')}`;
         CREATE TABLE `{$this->getTable('seller/feedbackbuyer')}` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `buyer_id` int(11) NOT NULL,
            `order_number` varchar(255) NOT NULL,
            `feedback` int(1) NOT NULL,
            `item_pay` int(1) NOT NULL,
            `communication` int(1) NOT NULL,
            `text` varchar(255) NOT NULL,
            PRIMARY KEY (`id`)           
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8
");
    
$this->endSetup();
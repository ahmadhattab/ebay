<?php
$this->startSetup();
$this->run("
        DROP TABLE IF EXISTS `{$this->getTable('seller/feedback')}`;
         CREATE TABLE `{$this->getTable('seller/feedback')}` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `order_number` varchar(255) NOT NULL,
            `seller_id` int(11) NOT NULL,                                    
            `buyer_id` int(11) NOT NULL,
            `status` int(1) NOT NULL,
            PRIMARY KEY (`id`)           
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8
");
    
$this->endSetup();
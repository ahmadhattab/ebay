<?php
$this->startSetup();
$this->run("
        DROP TABLE IF EXISTS `{$this->getTable('seller_setup')}`;
         CREATE TABLE `{$this->getTable('seller_setup')}` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `seller_id` int(11) NOT NULL,
            `product_title` varchar(255) NOT NULL,                                    
            `categories_ids` text,
            `item_conditions` text,
            `price` float(11) NOT NULL,
            `qty` int(11) NOT NULL,
            `images` text,
            `description` text,
            `payment_method` varchar(255) NOT NULL,                                    
            `paypal_email` varchar(255) NOT NULL,                                    
            `delivery_details` text,                                    
            `warrenty` text,                                    
            `retains` text,                                    
            `status` varchar(255) NOT NULL,                                                
            `created_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`)           
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8
");
    
$this->endSetup();

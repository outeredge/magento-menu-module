<?php

$this->startSetup();

// Custom Menu
$this->run("
    CREATE TABLE IF NOT EXISTS {$this->getTable('menu/menu')} (
        `id` int(11) primary key NOT NULL auto_increment,
        `title` text NULL DEFAULT NULL,
        `url` text NULL DEFAULT NULL,
        `image` text NULL DEFAULT NULL,
        `parent` int(11) NULL DEFAULT NULL,
        `type` ENUM('category','product','cms','custom') NOT NULL,
        `is_html` tinyint(1) NOT NULL,
        `html` text NULL DEFAULT NULL,
        `sort` int(11) NOT NULL DEFAULT '0',
        KEY `parent` (`parent`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$this->run("
    ALTER TABLE {$this->getTable('menu/menu')}
        ADD CONSTRAINT `menu_ibfk_1` FOREIGN KEY (`parent`) REFERENCES {$this->getTable('menu/menu')} (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
");

$this->endSetup();
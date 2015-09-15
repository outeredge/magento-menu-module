<?php

$this->startSetup()->run("

    ALTER TABLE {$this->getTable('menu/menu')}
        ADD COLUMN `website_id` SMALLINT(5) NOT NULL;

")->endSetup();
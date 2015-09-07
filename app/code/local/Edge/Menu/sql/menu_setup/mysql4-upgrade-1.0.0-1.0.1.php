<?php

$this->startSetup();
$this->run("
    ALTER TABLE {$this->getTable('menu/menu')}
        ADD COLUMN `class` text NULL DEFAULT NULL;
");
$this->endSetup();
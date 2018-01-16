<?php

namespace OuterEdge\Menu\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.0.1', '<')) {
            $this->addUseSubcategoriesColumnToItemTable($setup);
        }

        $setup->endSetup();
    }

    /**
     * Add the column 'use_subcategories' to the MenuItem table
     * It allows to use subcategory tree on a menu item
     *
     * @param SchemaSetupInterface $setup
     *
     * @return void
     */
    private function addUseSubcategoriesColumnToItemTable(SchemaSetupInterface $setup)
    {
        $connection = $setup->getConnection();

        $tableItem = $setup->getTable('menu_item');
        if ($connection->isTableExists($tableItem) == true) {
            $connection->addColumn(
                $tableItem,
                'use_subcategories',
                [
                    'type'     => Table::TYPE_SMALLINT,
                    'nullable' => false,
                    'default'  => 0,
                    'comment'  => 'Use Subcategory Tree'
                ]
            );
        }
    }
}

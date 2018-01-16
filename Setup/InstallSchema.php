<?php

namespace OuterEdge\Menu\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\DB\Adapter\AdapterInterface;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        $tableMenu = $installer->getTable('menu');
        if ($installer->getConnection()->isTableExists($tableMenu) != true) {
            $table = $installer->getConnection()
                ->newTable($tableMenu)
                ->addColumn(
                    'menu_id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true
                    ],
                    'Id'
                )
                ->addColumn(
                    'code',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false, 'default' => null],
                    'Menu Code'
                )
                ->addColumn(
                    'name',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => true, 'default' => null],
                    'Name'
                )
                ->addColumn(
                    'sort_order',
                    Table::TYPE_INTEGER,
                    null,
                    ['nullable' => false, 'default' => '0'],
                    'Sort Order'
                )
                ->addIndex(
                    $installer->getIdxName(
                        'menu',
                        ['code'],
                        AdapterInterface::INDEX_TYPE_UNIQUE
                    ),
                    ['code'],
                    ['type' => AdapterInterface::INDEX_TYPE_UNIQUE]
                )
                ->setComment('Menu Table')
                ->setOption('type', 'InnoDB')
                ->setOption('charset', 'utf8');
            $installer->getConnection()->createTable($table);
        }

        $tableItem = $installer->getTable('menu_item');
        if ($installer->getConnection()->isTableExists($tableItem) != true) {
            $table = $installer->getConnection()
                ->newTable($tableItem)
                ->addColumn(
                    'item_id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary'  => true
                    ],
                    'Id'
                )
                ->addColumn(
                    'menu_id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'unsigned' => true,
                        'nullable' => false
                    ],
                    'Menu Id'
                )
                ->addColumn(
                    'parent_id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'unsigned' => true,
                        'nullable' => true
                    ],
                    'Parent Id'
                )
                ->addColumn(
                    'title',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false],
                    'Title'
                )
                ->addColumn(
                    'description',
                    Table::TYPE_TEXT,
                    '2M',
                    ['nullable' => true],
                    'Description'
                )
                ->addColumn(
                    'url',
                    Table::TYPE_TEXT,
                    '2M',
                    ['nullable' => true],
                    'Url'
                )
                ->addColumn(
                    'image',
                    Table::TYPE_TEXT,
                    '2M',
                    ['nullable' => true],
                    'Image'
                )
                ->addColumn(
                    'product_id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'unsigned' => true,
                        'nullable' => true
                    ],
                    'Product Id'
                )
                ->addColumn(
                    'category_id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'unsigned' => true,
                        'nullable' => true
                    ],
                    'Category Id'
                )
                ->addColumn(
                    'page_id',
                    Table::TYPE_SMALLINT,
                    null,
                    ['nullable' => true],
                    'Page Id'
                )
                ->addColumn(
                    'sort_order',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'default'  => 0,
                        'nullable' => false
                    ],
                    'Sort Order'
                )
                ->addForeignKey(
                    $installer->getFkName('menu_item', 'menu_id', 'menu', 'menu_id'),
                    'menu_id',
                    $installer->getTable('menu'),
                    'menu_id',
                    Table::ACTION_CASCADE
                )
                ->addForeignKey(
                    $installer->getFkName('menu_item', 'parent_id', 'menu_item', 'item_id'),
                    'parent_id',
                    $installer->getTable('menu_item'),
                    'item_id',
                    Table::ACTION_CASCADE
                )
                ->addForeignKey(
                    $installer->getFkName('menu_item', 'product_id', 'catalog_product_entity', 'entity_id'),
                    'product_id',
                    $installer->getTable('catalog_product_entity'),
                    'entity_id',
                    Table::ACTION_CASCADE
                )
                ->addForeignKey(
                    $installer->getFkName('menu_item', 'category_id', 'catalog_category_entity', 'entity_id'),
                    'category_id',
                    $installer->getTable('catalog_category_entity'),
                    'entity_id',
                    Table::ACTION_CASCADE
                )
                ->addForeignKey(
                    $installer->getFkName('menu_item', 'page_id', 'cms_page', 'page_id'),
                    'page_id',
                    $installer->getTable('cms_page'),
                    'page_id',
                    Table::ACTION_CASCADE
                )
                ->setComment('Menu Item Table')
                ->setOption('type', 'InnoDB')
                ->setOption('charset', 'utf8');
            $installer->getConnection()->createTable($table);
        }

        $installer->endSetup();
    }
}

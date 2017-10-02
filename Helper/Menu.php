<?php

namespace OuterEdge\Menu\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use OuterEdge\Menu\Model\MenuFactory as MenuFactory;
use OuterEdge\Menu\Model\ResourceModel\Item\CollectionFactory as ItemCollectionFactory;
use OuterEdge\Menu\Model\Menu as MenuModel;
use Magento\Framework\Escaper;

class Menu extends AbstractHelper
{
    /**
     * @var MenuFactory
     */
    private $menuFactory;

    /**
     * @var ItemCollectionFactory
     */
    private $itemCollectionFactory;

    /**
     * @var Escaper
     */
    private $escaper;

    /**
     * @param MenuFactory $menuFactory
     * @param ItemCollectionFactory $itemCollectionFactory
     * @param Escaper $escaper
     * @codeCoverageIgnore
     */
    public function __construct(
        MenuFactory $menuFactory,
        ItemCollectionFactory $itemCollectionFactory,
        Escaper $escaper
    ) {
        $this->menuFactory = $menuFactory;
        $this->itemCollectionFactory = $itemCollectionFactory;
        $this->escaper = $escaper;
    }

    public function getMenuHtml($menu, $includeWrapper = false, $loadByCode = false)
    {
        if ($menu instanceof MenuModel) {
            return $this->_getMenuHtml($menu, $includeWrapper);
        }

        $menuModel = $this->menuFactory->create();
        if ($loadByCode) {
            $menuModel->loadByCode($menu);
        } else {
            $menuModel->load($menu);
        }
        if (!$menuModel->getId()) {
            return;
        }

        return $this->_getMenuHtml($menuModel, $includeWrapper);
    }

    protected function _getMenuHtml($menu, $includeWrapper = false)
    {
        $items = $this->itemCollectionFactory->create()
            ->addFieldToFilter('menu_id', ['eq' => $menu->getId()])
            ->addFieldToFilter('parent_id', ['null' => true]);

        $menuHtml = $this->_addSubMenu($items);

        if ($includeWrapper) {
            return '<ul>' . $menuHtml . '</ul>';
        }
        return $menuHtml;
    }

    protected function _addSubMenu($items, $level = 0)
    {
        $html = '';

        foreach ($items as $item) {
            $children = $this->_getItemChildren($item);

            $item->setLevel($level);
            $item->setChildren($children->count());

            $itemClass = $this->_getItemClasses($item, $level);
            $itemTitle = $this->escaper->escapeHtml($item->getTitle());

            $html .= '<li class="' . $itemClass . '" title="' . $itemTitle . '">';
            $html .= '<a class="' . ($item->getLevel() === 0 ? 'level-top' : '') . '" href="' . $item->getLink() . '">';
            $html .= '<span class="title">' . $item->getTitle();
            if ($item->getDescription()) {
                $html .= '<span class="description">' . $item->getDescription() . '</span>';
            }
            $html .= '</span>';
            if ($item->getImage()) {
                $html .= '<span class="image"><img src="/media/' . $item->getImage() . '" alt=""></span>';
            }
            $html .= '</a>';
            if ($children->count()) {
                $html .= '<ul class="level' . $level . ' submenu">';
                $html .= $this->_addSubMenu($children, $level + 1);
                $html .= '</ul>';
            }
             $html .= '</li>';
        }

        $html .= '';

        return $html;
    }

    protected function _getItemClasses($item)
    {
        $classes = ['level' . $item->getLevel()];
        if ($item->getLevel() === 0) {
            $classes[] = 'level-top';
        }
        if ($item->getChildren()) {
            $classes[] = 'parent';
        }
        if ($item->getImage()) {
            $classes[] = 'has-image';
        }
        return implode(' ', $classes);
    }

    protected function _getItemChildren($item)
    {
        return $this->itemCollectionFactory->create()
            ->addFieldToFilter('parent_id', ['eq' => $item->getId()]);
    }
}

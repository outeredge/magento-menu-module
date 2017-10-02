<?php

namespace OuterEdge\Menu\Plugin\Theme\Block\Html;

use OuterEdge\Menu\Helper\Menu as MenuHelper;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Theme\Block\Html\Topmenu as HtmlTopmenu;

class Topmenu
{
    /**
     * @var MenuHelper
     */
    private $menuHelper;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param MenuHelper $menuHelper
     * @param ScopeConfigInterface $scopeConfig
     * @codeCoverageIgnore
     */
    public function __construct(
        MenuHelper $menuHelper,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->menuHelper = $menuHelper;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Get custom menu html or fallback to original magento
     *
     * @param HtmlTopmenu $subject
     * @param callable $proceed
     * @param string $outermostClass
     * @param string $childrenWrapClass
     * @param int $limit
     * @return string
     */
    public function aroundGetHtml(HtmlTopmenu $subject, callable $proceed, ...$args)
    {
        if (!$this->scopeConfig->getValue('menu/menu/enable_override', ScopeInterface::SCOPE_STORE)) {
            return $proceed(...$args);
        }
        return $this->menuHelper->getMenuHtml(
            $this->scopeConfig->getValue('menu/menu/use_menu', ScopeInterface::SCOPE_STORE)
        );
    }
}

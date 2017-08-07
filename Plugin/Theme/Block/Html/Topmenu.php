<?php

namespace OuterEdge\Menu\Block\Html;

use Magento\Theme\Block\Html\Topmenu as HtmlTopmenu;

class Topmenu
{
    public function afterGetHtml(HtmlTopmenu $subject, $result)
    {
        return str_replace(array('<ul','</ul>','<li','</li>'), array('<div><div','</div></div>','<div','</div>'), $result);
    }
}

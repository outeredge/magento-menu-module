<?php

namespace OuterEdge\Menu\Block\Html;

use Magento\Theme\Block\Html\Topmenu as HtmlTopmenu;

class Topmenu
{
    public function afterGetHtml(HtmlTopmenu $subject, $result)
    {
        return str_replace(['<ul','</ul>','<li','</li>'], ['<div><div','</div></div>','<div','</div>'], $result);
    }
}

<?php

namespace OuterEdge\Menu \Block\Html;

use Magento\Theme\Block\Html\Topmenu;

class TopmenuPlugin
{
    public function afterGetHtml(Topmenu $subject, $result)
    {
        return str_replace(array('<ul','</ul>','<li','</li>'), array('<div><div','</div></div>','<div','</div>'), $result);
    }
}

<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


namespace Amasty\QuickOrder\Block;

use Magento\Framework\View\Element\Template;

class Popup extends Template
{
    public function getJsLayout()
    {
        return json_encode($this->jsLayout, JSON_HEX_TAG);
    }
}

<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


declare(strict_types=1);

namespace Amasty\QuickOrder\Block\Adminhtml\System\Config\Field;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class Multiselect extends Field
{
    /**
     * @param AbstractElement $element
     *
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $element->setData('size', count($element->getValues()) ?: 10);
        $html = $element->getElementHtml();
        if ($element->getCanBeEmpty()) {
            $html = str_replace('type="hidden"', 'type="text" class="amasty-hidden-field"', $html);
        }

        return $html;
    }
}

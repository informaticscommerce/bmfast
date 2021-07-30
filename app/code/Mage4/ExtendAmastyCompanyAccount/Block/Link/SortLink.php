<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Mage4\ExtendAmastyCompanyAccount\Block\Link;

/**
 * Class for sortable links.
 */
class SortLink extends \Amasty\CompanyAccount\Block\Link\AbstractLink
{
    /**
     * Render block HTML
     *
     * @return string
     */
    protected function _toHtml()
    {
        if (!$this->isAllowed()) {
            return '';
        }

        if (false != $this->getTemplate()) {
            return parent::_toHtml();
        }

        $highlight = '';

        if ($this->getIsHighlighted()) {
            $highlight = ' current';
        }

        if ($this->isCurrent()) {
            /*
             * custom work,
             * append label in class
             * */
            $labelString = str_replace('/', '-', strtolower($this->getLabel()));
            $html = '<li class="nav item current '.str_replace(' ', '-', $labelString).'">';
            $html .= '<strong>'
                . $this->escapeHtml(__($this->getLabel()))
                . '</strong>';
            $html .= '</li>';
        } else {
            /*
             * custom work,
             * append label in class
             * */
            $labelString = str_replace('/', '-', strtolower($this->getLabel()));
            $html = '<li class="nav item ' .str_replace(' ', '-', $labelString). $highlight . '"><a href="' . $this->escapeHtml($this->getHref()) . '"';
            $html .= $this->getTitle()
                ? ' title="' . $this->escapeHtml(__($this->getTitle())) . '"'
                : '';
            $html .= $this->getAttributesHtml() . '>';

            if ($this->getIsHighlighted()) {
                $html .= '<strong>';
            }

            $html .= $this->escapeHtml(__($this->getLabel()));

            if ($this->getIsHighlighted()) {
                $html .= '</strong>';
            }

            $html .= '</a></li>';
        }

        return $html;
    }

}

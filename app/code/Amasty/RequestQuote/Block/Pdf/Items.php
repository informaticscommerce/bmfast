<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_RequestQuote
 */


declare(strict_types=1);

namespace Amasty\RequestQuote\Block\Pdf;

class Items extends \Magento\Framework\View\Element\Template
{
    /**
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function toHtml()
    {
        $quoteItems = $this->getLayout()->getBlock('quote_items');

        return $quoteItems->toHtml();
    }
}

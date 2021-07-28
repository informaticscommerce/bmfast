<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_RequestQuote
 */


namespace Amasty\RequestQuote\Block\Adminhtml\Quote\Create\Newsletter;

class Form extends \Magento\Backend\Block\Widget
{
    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('sales_order_create_newsletter_form');
    }
}

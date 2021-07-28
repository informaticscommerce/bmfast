<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_RequestQuote
 */


declare(strict_types=1);

namespace Amasty\RequestQuote\Plugin\CustomerCustomAttributes\Helper;

use Magento\CustomerCustomAttributes\Helper\Data;

class DataPlugin
{
    const QUOTE_FORM = 'quote_cart_form';

    /**
     * @param Data $subject
     * @param array $options
     * @return array
     */
    public function afterGetCustomerAttributeFormOptions(Data $subject, array $options)
    {
        $options[] = [
            'value' => self::QUOTE_FORM,
            'label' => __('Amasty \'Request a Quote\' form')
        ];

        return $options;
    }
}

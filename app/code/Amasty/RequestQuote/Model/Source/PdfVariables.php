<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_RequestQuote
 */


declare(strict_types=1);

namespace Amasty\RequestQuote\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

class PdfVariables implements OptionSourceInterface
{
    const USERNAME = 'username';
    const CUSTOMER_STREET = 'customer_street';
    const CUSTOMER_CITY = 'customer_city';
    const CUSTOMER_REGION = 'customer_region';
    const CUSTOMER_POSTCODE = 'customer_postcode';
    const CUSTOMER_COUNTRY = 'customer_country';
    const CUSTOMER_TELEPHONE = 'customer_telephone';
    const QUOTE_NUMBER = 'quote_number';
    const QUOTE_STATUS = 'quote_status';
    const QUOTE_DATE = 'quote_date';
    const QUOTE_EXPIRY_DATE = 'quote_expiry_date';
    const HAS_SHIPPING_INFO = 'has_shipping_info';
    const SHIPPING_STREET = 'shipping_street';
    const SHIPPING_CITY = 'shipping_city';
    const SHIPPING_REGION = 'shipping_region';
    const SHIPPING_POSTCODE = 'shipping_postcode';
    const SHIPPING_COUNTRY = 'shipping_country';
    const SHIPPING_TELEPHONE = 'shipping_telephone';
    const SHIPPING_METHOD = 'shipping_method';
    const PRODUCT_GRID = 'product_grid';
    const STORE_PHONE = 'store_phone';

    /**
     * @return array|array[]
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::USERNAME,
                'label' => __('Username')
            ],
            [
                'value' => self::CUSTOMER_STREET,
                'label' => __('Customer Street')
            ],
            [
                'value' => self::CUSTOMER_REGION,
                'label' => __('Customer Region')
            ],
            [
                'value' => self::CUSTOMER_POSTCODE,
                'label' => __('Customer Postcode')
            ],
            [
                'value' => self::CUSTOMER_COUNTRY,
                'label' => __('Customer Country')
            ],
            [
                'value' => self::CUSTOMER_TELEPHONE,
                'label' => __('Customer Telephone')
            ],
            [
                'value' => self::QUOTE_NUMBER,
                'label' => __('Quote Number')
            ],
            [
                'value' => self::QUOTE_STATUS,
                'label' => __('Quote Status')
            ],
            [
                'value' => self::QUOTE_DATE,
                'label' => __('Quote Date')
            ],
            [
                'value' => self::QUOTE_EXPIRY_DATE,
                'label' => __('Quote Expiry Date')
            ],
            [
                'value' => self::HAS_SHIPPING_INFO,
                'label' => __('Has Shipping Info')
            ],
            [
                'value' => self::SHIPPING_STREET,
                'label' => __('Shipping Street')
            ],
            [
                'value' => self::SHIPPING_CITY,
                'label' => __('Shipping City')
            ],
            [
                'value' => self::SHIPPING_REGION,
                'label' => __('Shipping Region')
            ],
            [
                'value' => self::SHIPPING_POSTCODE,
                'label' => __('Shipping Postcode')
            ],
            [
                'value' => self::SHIPPING_COUNTRY,
                'label' => __('Shipping Country')
            ],
            [
                'value' => self::SHIPPING_TELEPHONE,
                'label' => __('Shipping Telephone')
            ],
            [
                'value' => self::SHIPPING_METHOD,
                'label' => __('Shipping Method')
            ],
            [
                'value' => self::PRODUCT_GRID,
                'label' => __('Product Grid')
            ],
            [
                'value' => self::STORE_PHONE,
                'label' => __('Store Phone')
            ],
        ];
    }
}

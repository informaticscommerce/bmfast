<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_RequestQuote
 */


namespace Amasty\RequestQuote\Api\Data;

interface QuoteInterface
{
    const MAIN_TABLE = 'amasty_quote';

    /**
     * @TODO need to add extends of  \Magento\Quote\Api\Data\CartInterface
     */
    const STATUS = 'status';
    const EXPIRED_DATE = 'expired_date';
    const REMINDER_DATE = 'reminder_date';
    const ADMIN_NOTIFICATION_SEND = 'admin_notification_send';
    const ADMIN_NOTE_KEY = 'admin_note';
    const CUSTOMER_NOTE_KEY = 'customer_note';
    const DISCOUNT = 'discount';
    const SURCHARGE = 'surcharge';
    const REMINDER_SEND = 'reminder_send';
    const SUBMITED_DATE = 'submited_date';
    const SHIPPING_CAN_BE_MODIFIED = 'shipping_can_modified';
    const SHIPPING_CONFIGURE = 'shipping_configured';
    const CUSTOM_FEE = 'custom_fee';
    const CUSTOM_METHOD_ENABLED = 'custom_method_enabled';
    const SUM_ORIGINAL_PRICE = 'sum_original_price';
}

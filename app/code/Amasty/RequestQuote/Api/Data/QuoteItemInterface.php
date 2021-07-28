<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_RequestQuote
 */


namespace Amasty\RequestQuote\Api\Data;

interface QuoteItemInterface
{
    const ADMIN_NOTE_KEY = 'admin_note';
    const CUSTOMER_NOTE_KEY = 'customer_note';
    const REQUESTED_PRICE = 'requested_price';
    const CUSTOM_PRICE = 'requested_custom_price';
    const HIDE_ORIGINAL_PRICE = 'hide_original_price';
}

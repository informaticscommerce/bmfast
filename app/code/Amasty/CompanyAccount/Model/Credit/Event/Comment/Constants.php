<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_CompanyAccount
 */


declare(strict_types=1);

namespace Amasty\CompanyAccount\Model\Credit\Event\Comment;

class Constants
{
    const ORDER_INCREMENT = 'order';
    const DISPLAY_CURRENCY = 'currency_display';
    const BASE_CURRENCY = 'currency_base';
    const COMMENT = 'comment';
    const OVERDRAFT_SUM = 'overdraft_sum';
    const REPAY_DATE = 'repay_date';
}

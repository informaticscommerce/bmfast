<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_CompanyAccount
 */


declare(strict_types=1);

namespace Amasty\CompanyAccount\Model\Credit\Event\Query;

use Amasty\CompanyAccount\Api\Data\CreditEventInterface;

/**
 * @api
 */
interface CreateCreditEventInterface
{
    public function execute(array $data): CreditEventInterface;
}

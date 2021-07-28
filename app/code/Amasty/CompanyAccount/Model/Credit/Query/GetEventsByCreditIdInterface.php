<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_CompanyAccount
 */


declare(strict_types=1);

namespace Amasty\CompanyAccount\Model\Credit\Query;

use Amasty\CompanyAccount\Model\ResourceModel\CreditEvent\Collection as CreditEventCollection;

interface GetEventsByCreditIdInterface
{
    public function execute(int $creditId): CreditEventCollection;
}

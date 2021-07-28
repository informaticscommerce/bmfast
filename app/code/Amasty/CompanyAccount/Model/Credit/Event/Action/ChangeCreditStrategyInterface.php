<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_CompanyAccount
 */


declare(strict_types=1);

namespace Amasty\CompanyAccount\Model\Credit\Event\Action;

use Amasty\CompanyAccount\Api\Data\CreditEventInterface;
use Amasty\CompanyAccount\Api\Data\CreditInterface;

interface ChangeCreditStrategyInterface
{
    public function execute(CreditInterface $credit, CreditEventInterface $creditEvent): void;
}

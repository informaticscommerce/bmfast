<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_CompanyAccount
 */


declare(strict_types=1);

namespace Amasty\CompanyAccount\Model\Credit\Query;

use Amasty\CompanyAccount\Api\Data\CreditInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * @api
 */
interface GetByCompanyIdInterface
{
    /**
     * @param int $companyId
     * @return CreditInterface
     * @throws NoSuchEntityException
     */
    public function execute(int $companyId): CreditInterface;
}

<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_CompanyAccount
 */


declare(strict_types=1);

namespace Amasty\CompanyAccount\Model\Credit\Overdraft\Query;

use Amasty\CompanyAccount\Api\Data\OverdraftInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * @api
 */
interface GetByCreditIdInterface
{
    /**
     * @param int $creditId
     * @return OverdraftInterface
     * @throws NoSuchEntityException
     */
    public function execute(int $creditId): OverdraftInterface;
}

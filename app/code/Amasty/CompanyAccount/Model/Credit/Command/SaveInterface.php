<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_CompanyAccount
 */


declare(strict_types=1);

namespace Amasty\CompanyAccount\Model\Credit\Command;

use Amasty\CompanyAccount\Api\Data\CreditInterface;
use Magento\Framework\Exception\CouldNotSaveException;

/**
 * @api
 */
interface SaveInterface
{
    /**
     * @param CreditInterface $credit
     * @return void
     * @throws CouldNotSaveException
     */
    public function execute(CreditInterface $credit): void;
}

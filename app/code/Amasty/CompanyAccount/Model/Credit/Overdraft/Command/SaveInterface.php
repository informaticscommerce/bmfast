<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_CompanyAccount
 */


declare(strict_types=1);

namespace Amasty\CompanyAccount\Model\Credit\Overdraft\Command;

use Amasty\CompanyAccount\Api\Data\OverdraftInterface;
use Magento\Framework\Exception\CouldNotSaveException;

/**
 * @api
 */
interface SaveInterface
{
    /**
     * @param OverdraftInterface $overdraft
     * @return void
     * @throws CouldNotSaveException
     */
    public function execute(OverdraftInterface $overdraft): void;
}

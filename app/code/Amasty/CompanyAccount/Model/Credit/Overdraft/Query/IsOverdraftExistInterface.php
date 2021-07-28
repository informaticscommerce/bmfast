<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_CompanyAccount
 */


declare(strict_types=1);

namespace Amasty\CompanyAccount\Model\Credit\Overdraft\Query;

/**
 * @api
 */
interface IsOverdraftExistInterface
{
    public function execute(int $creditId): bool;
}

<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_CompanyAccount
 */


declare(strict_types=1);

namespace Amasty\CompanyAccount\Model\Company\Role\Acl;

class DefaultStrategy implements IsAclShowedStrategyInterface
{
    public function execute(): bool
    {
        return true;
    }
}

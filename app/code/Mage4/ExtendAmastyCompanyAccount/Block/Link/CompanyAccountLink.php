<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_CompanyAccount
 */


declare(strict_types=1);

namespace Mage4\ExtendAmastyCompanyAccount\Block\Link;

class CompanyAccountLink extends SortLink
{
    /**
     * @return bool
     */
    protected function isAllowed()
    {
        return $this->companyContext->isCreateCompanyAllowed()
            || ($this->companyContext->isCurrentUserCompanyUser() && parent::isAllowed());
    }
}

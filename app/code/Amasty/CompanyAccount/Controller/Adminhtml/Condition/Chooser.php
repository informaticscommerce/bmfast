<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_CompanyAccount
 */


declare(strict_types=1);

namespace Amasty\CompanyAccount\Controller\Adminhtml\Condition;

use Magento\Backend\App\Action;

class Chooser extends Action
{
    const ADMIN_RESOURCE = 'Magento_SalesRule::quote';

    public function execute()
    {
        $block = $this->_view->getLayout()->createBlock(
            \Amasty\CompanyAccount\Block\Adminhtml\Condition\Chooser::class,
            'amasty_company_chooser',
            ['data' => ['js_form_object' => $this->getRequest()->getParam('form')]]
        );

        if ($block) {
            $this->getResponse()->setBody($block->toHtml());
        }
    }
}

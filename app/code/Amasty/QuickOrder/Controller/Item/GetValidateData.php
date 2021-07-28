<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


declare(strict_types=1);

namespace Amasty\QuickOrder\Controller\Item;

use Magento\Framework\Controller\ResultInterface;
use Zend\Http\Response;

class GetValidateData extends AbstractAction
{
    /**
     * @return ResultInterface
     */
    protected function action()
    {
        if (!$this->getRequest()->isPost()) {
            return $this->generateResult(Response::STATUS_CODE_400, [
                'message' => $this->escape(__('Request method type not supported.'))
            ]);
        }

        return $this->generateResult(Response::STATUS_CODE_200, [
            'items_count' => $this->getItemManager()->getItemsCount()
        ]);
    }
}

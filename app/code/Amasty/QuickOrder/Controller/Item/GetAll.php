<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


declare(strict_types=1);

namespace Amasty\QuickOrder\Controller\Item;

use Exception;
use Magento\Framework\Controller\ResultInterface;
use Zend\Http\Response;

class GetAll extends AbstractAction
{
    /**
     * @return ResultInterface
     */
    public function action()
    {
        if (!$this->getRequest()->isPost()) {
            return $this->generateResult(Response::STATUS_CODE_400, [
                'message' => $this->escape(__('Request method type not supported.'))
            ]);
        }

        try {
            return $this->generateResult(
                Response::STATUS_CODE_200,
                $this->getProductProvider()->getAllProductsInfo()
            );
        } catch (Exception $e) {
            $this->getLogger()->error($e->getMessage());
            return $this->generateResult(Response::STATUS_CODE_400, [
                'message' => $this->escape(__('Something is wrong.'))
            ]);
        }
    }
}

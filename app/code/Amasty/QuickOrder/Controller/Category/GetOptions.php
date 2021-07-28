<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


declare(strict_types=1);

namespace Amasty\QuickOrder\Controller\Category;

use Amasty\QuickOrder\Model\Category\OptionsProvider;
use Exception;
use Magento\Framework\Controller\ResultInterface;
use Zend\Http\Response;

class GetOptions extends AbstractAction
{
    const PRODUCT_IDS = 'product_ids';

    protected function action(): ResultInterface
    {
        if (!$this->getRequest()->isGet()) {
            return $this->generateResult(Response::STATUS_CODE_400, [
                'message' => $this->escape(__('Request method type not supported.'))
            ]);
        }

        try {
            return $this->generateResult(
                Response::STATUS_CODE_200,
                $this->getOptionsProvider()->getOptions($this->getProductIds())
            );
        } catch (Exception $e) {
            $this->getLogger()->error($e->getMessage());
            return $this->generateResult(Response::STATUS_CODE_400, [
                'message' => $this->escape(__('Something is wrong.'))
            ]);
        }
    }

    private function getProductIds(): array
    {
        return $this->getRequest()->getParam(static::PRODUCT_IDS, []);
    }

    protected function getOptionsProvider(): OptionsProvider
    {
        return $this->getData('optionsProvider');
    }
}

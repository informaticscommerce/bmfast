<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


declare(strict_types=1);

namespace Amasty\QuickOrder\Controller\Item\Import;

use Magento\Framework\Exception\LocalizedException;

class Add extends AbstractAction
{
    const INPUT_NAME = 'item_data';

    /**
     * @return array
     * @throws LocalizedException
     */
    public function importAction(): array
    {
        if ($itemData = $this->getItemData()) {
            return $this->getItemManager()->addItem($itemData);
        } else {
            throw new LocalizedException(__('Item data not provided.'));
        }
    }

    /**
     * @return array
     */
    private function getItemData(): array
    {
        return (array) $this->getRequest()->getParam(static::INPUT_NAME, []);
    }

    /**
     * @return int
     */
    public function calculateTotalQty(): int
    {
        return 1;
    }
}

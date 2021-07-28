<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


declare(strict_types=1);

namespace Amasty\QuickOrder\CustomerData;

use Amasty\QuickOrder\Model\Category\Item\Manager as ItemManager;
use Magento\Customer\CustomerData\SectionSourceInterface;

class Category implements SectionSourceInterface
{
    /**
     * @var ItemManager
     */
    private $itemManager;

    public function __construct(ItemManager $itemManager)
    {
        $this->itemManager = $itemManager;
    }

    public function getSectionData()
    {
        return $this->itemManager->getItems();
    }
}

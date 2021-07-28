<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


declare(strict_types=1);

namespace Amasty\QuickOrder\Model\Category\Item;

use Magento\Framework\Session\SessionManager;

class Session extends SessionManager
{
    const ITEMS = 'current_items';

    public function getItems(): array
    {
        return $this->getData(self::ITEMS) ?: [];
    }

    public function setItems(array $items): Session
    {
        $this->setData(self::ITEMS, $items);
        return $this;
    }

    public function setItem(int $itemId, array $data): void
    {
        $items = $this->getItems();
        $items[$itemId] = $data;
        $this->setItems($items);
    }

    public function getItem(int $itemId): array
    {
        $items = $this->getItems();

        return $items[$itemId] ?? ['id' => $itemId, 'product_id' => $itemId];
    }

    public function clear()
    {
        $this->setItems([]);
    }

    public function removeItem(int $itemId): Session
    {
        $items = $this->getItems();
        unset($items[$itemId]);

        return $this->setItems($items);
    }
}

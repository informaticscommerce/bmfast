<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


declare(strict_types=1);

namespace Amasty\QuickOrder\Model\Cart;

use Magento\Checkout\Model\Cart;
use Magento\Checkout\Model\Cart\CartInterface;
use Magento\Framework\ObjectManagerInterface;

class AddProductsPool
{
    const FROM_GRID = 'from_grid';
    const FROM_CATEGORY = 'from_category';

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var CartInterface|Cart|QuoteCart
     */
    private $cart;

    /**
     * @var string[]
     */
    private $instanceMap;

    /**
     * @var array
     */
    private $instances = [];

    public function __construct(ObjectManagerInterface $objectManager, CartInterface $cart, array $instanceMap = [])
    {
        $this->objectManager = $objectManager;
        $this->cart = $cart;
        $this->instanceMap = $instanceMap;
    }

    public function get(string $type): AddProductsInterface
    {
        if (!isset($this->instances[$type])) {
            $this->instances[$type] = $this->objectManager->create($this->instanceMap[$type], [
                'cart' => $this->cart
            ]);
        }

        return $this->instances[$type];
    }
}

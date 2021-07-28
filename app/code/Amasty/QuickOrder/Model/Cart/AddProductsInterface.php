<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


declare(strict_types=1);

namespace Amasty\QuickOrder\Model\Cart;

use Amasty\QuickOrder\Model\Cart\Result as CartResult;

interface AddProductsInterface
{
    public function execute(): CartResult;
}

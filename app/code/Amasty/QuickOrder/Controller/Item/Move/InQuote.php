<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


declare(strict_types=1);

namespace Amasty\QuickOrder\Controller\Item\Move;

class InQuote extends AbstractAction
{
    /**
     * @return string
     */
    public function getRedirectAction(): string
    {
        return 'amasty_quote/cart';
    }
}

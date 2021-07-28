<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


declare(strict_types=1);

namespace Amasty\QuickOrder\Model\Item\Block\Renderer;

/**
 * Used for detecting when layout used for render blocks for quickorder.
 *
 * Class Flag
 */
class Flag
{
    /**
     * @var bool
     */
    private $flag = false;

    public function isActive(): bool
    {
        return $this->flag;
    }

    public function enable(): void
    {
        $this->flag = true;
    }

    public function disable(): void
    {
        $this->flag = false;
    }
}

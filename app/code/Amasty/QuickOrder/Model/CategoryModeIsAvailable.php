<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


declare(strict_types=1);

namespace Amasty\QuickOrder\Model;

class CategoryModeIsAvailable implements IsAvailableInterface
{
    /**
     * @var CategoryMode
     */
    private $categoryMode;

    public function __construct(CategoryMode $categoryMode)
    {
        $this->categoryMode = $categoryMode;
    }

    public function execute(): bool
    {
        return $this->categoryMode->isAvailable();
    }
}

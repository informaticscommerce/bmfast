<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


declare(strict_types=1);

namespace Amasty\QuickOrder\Block\Script;

interface ConfigInterface
{
    public function getJsonConfig(): string;

    public function setItemId(int $itemId): void;

    public function getItemId(): int;

    /**
     * @return string
     */
    public function toHtml();
}

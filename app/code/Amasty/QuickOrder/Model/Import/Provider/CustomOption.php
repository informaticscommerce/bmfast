<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


declare(strict_types=1);

namespace Amasty\QuickOrder\Model\Import\Provider;

class CustomOption extends AbstractOptionProvider
{
    const TYPE = 'custom_option';
    const REQUEST_CODE = 'options';

    /**
     * @param string $title
     * @param string $sku
     * @return string|null
     */
    public function getOption(string $title, string $sku)
    {
        return $this->getOptionCache()[$title][$sku] ?? null;
    }

    /**
     * @param string $title
     * @param string $sku
     * @return string
     */
    public function getValue(string $title, string $sku)
    {
        return $this->getValueCache()[$title][$sku] ?? $title;
    }
}

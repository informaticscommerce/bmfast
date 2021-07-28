<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


declare(strict_types=1);

namespace Amasty\QuickOrder\Model\Import\Provider\Configurable;

use Amasty\QuickOrder\Model\Import\Provider\AbstractOptionProvider;

class Provider extends AbstractOptionProvider
{
    const TYPE = 'super_attribute';
    const REQUEST_CODE = 'super_attribute';

    /**
     * @inheritDoc
     */
    public function getOption(string $title, string $sku)
    {
        return $this->getOptionCache()[$title] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getValue(string $title, string $sku)
    {
        return $this->getValueCache()[$title] ?? null;
    }
}

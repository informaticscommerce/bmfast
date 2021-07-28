<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


declare(strict_types=1);

namespace Amasty\QuickOrder\Model\Import\Provider\Grouped;

use Amasty\QuickOrder\Model\Import\Provider\AbstractOptionProvider;

class Provider extends AbstractOptionProvider
{
    const TYPE = 'super_group';
    const REQUEST_CODE = 'super_group';

    /**
     * @inheritDoc
     */
    public function getOption(string $title, string $sku)
    {
        return $this->getOptionCache()[$title] ?? null;
    }

    /**
     * For Grouped Provider value is Child product qty.
     *
     * @inheritDoc
     */
    public function getValue(string $title, string $sku)
    {
        return $title;
    }
}

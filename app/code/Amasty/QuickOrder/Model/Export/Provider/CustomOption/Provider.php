<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


declare(strict_types=1);

namespace Amasty\QuickOrder\Model\Export\Provider\CustomOption;

use Amasty\QuickOrder\Model\Export\Provider\OptionProvider;

class Provider extends OptionProvider
{
    public function getValue(string $optionId): ?string
    {
        return $this->getValueCache()[$this->convertOptionId($optionId)] ?? $optionId;
    }
}

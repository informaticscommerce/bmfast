<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


declare(strict_types=1);

namespace Amasty\QuickOrder\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

class CategoryMode implements OptionSourceInterface
{
    const AS_DEFAULT = 0;
    const USE_DEFAULT = 1;
    const YES = 2;

    /**
     * @return array
     */
    public function toOptionArray(): array
    {
        return [
            [
                'value' => self::AS_DEFAULT,
                'label' => __('No (and use Table Mode as default)')
            ],
            [
                'value' => self::USE_DEFAULT,
                'label' => __('No (but use default settings)')
            ],
            [
                'value' => self::YES,
                'label' => __('Yes')
            ]
        ];
    }
}

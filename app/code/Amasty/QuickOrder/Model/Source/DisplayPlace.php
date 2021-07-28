<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


declare(strict_types=1);

namespace Amasty\QuickOrder\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

class DisplayPlace implements OptionSourceInterface
{
    const PAGE_HEADER = 0;
    const TOP_MENU = 1;
    const PAGE_FOOTER = 2;

    /**
     * @return array
     */
    public function toOptionArray(): array
    {
        return [
            [
                'value' => self::PAGE_HEADER,
                'label' => __('Page Header')
            ],
            [
                'value' => self::TOP_MENU,
                'label' => __('Top Menu')
            ],
            [
                'value' => self::PAGE_FOOTER,
                'label' => __('Page Footer')
            ]
        ];
    }
}

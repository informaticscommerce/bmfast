<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_CompanyAccount
 */


declare(strict_types=1);

namespace Amasty\CompanyAccount\Model\Source\Credit;

use Magento\Framework\Data\OptionSourceInterface;

class OverdraftRepayType implements OptionSourceInterface
{
    const DAY = 0;
    const MONTH = 1;
    const YEAR = 2;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::DAY,
                'label' => __('Day(s)')
            ],
            [
                'value' => self::MONTH,
                'label' => __('Month(s)')
            ],
            [
                'value' => self::YEAR,
                'label' => __('Year(s)')
            ]
        ];
    }

    public function toArray(): array
    {
        return [
            self::DAY => __('Day(s)'),
            self::MONTH => __('Month(s)'),
            self::YEAR => __('Year(s)')
        ];
    }
}

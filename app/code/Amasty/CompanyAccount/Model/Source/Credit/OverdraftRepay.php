<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_CompanyAccount
 */


declare(strict_types=1);

namespace Amasty\CompanyAccount\Model\Source\Credit;

use Magento\Framework\Data\OptionSourceInterface;

class OverdraftRepay implements OptionSourceInterface
{
    const UNLIMITED = 0;
    const SET = 1;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::UNLIMITED,
                'label' => __('Unlimited')
            ],
            [
                'value' => self::SET,
                'label' => __('Set')
            ]
        ];
    }
}

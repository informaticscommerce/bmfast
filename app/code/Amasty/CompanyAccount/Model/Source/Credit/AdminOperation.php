<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_CompanyAccount
 */


declare(strict_types=1);

namespace Amasty\CompanyAccount\Model\Source\Credit;

use Magento\Framework\Data\OptionSourceInterface;

class AdminOperation implements OptionSourceInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => Operation::PLUS_BY_ADMIN,
                'label' => __('Added by Admin')
            ],
            [
                'value' => Operation::MINUS_BY_ADMIN,
                'label' => __('Subtracted by Admin')
            ],
            [
                'value' => Operation::PLUS_BY_COMPANY,
                'label' => __('Repaid by Company')
            ]
        ];
    }
}

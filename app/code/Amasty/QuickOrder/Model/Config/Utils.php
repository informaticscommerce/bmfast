<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


declare(strict_types=1);

namespace Amasty\QuickOrder\Model\Config;

class Utils
{
    /**
     * @param string $multiselectValue
     * @return array
     */
    public function parseMultiselect(string $multiselectValue): array
    {
        return array_filter(
            explode(',', $multiselectValue),
            function ($elem) {
                return trim($elem) != '';
            }
        );
    }
}

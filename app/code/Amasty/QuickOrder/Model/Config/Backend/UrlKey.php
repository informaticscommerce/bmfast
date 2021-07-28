<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


declare(strict_types=1);

namespace Amasty\QuickOrder\Model\Config\Backend;

use Magento\Framework\App\Config\Value;

class UrlKey extends Value
{
    /**
     * @return Value
     */
    public function beforeSave()
    {
        if ($this->isValueChanged()) {
            $this->setValue($this->updateValue((string) $this->getValue()));
        }

        return parent::beforeSave();
    }

    /**
     * @param string $value
     * @return string
     */
    private function updateValue(string $value): string
    {
        if ($value) {
            $value = array_filter(explode(' ', $value));
            $value = implode('-', $value);
        }

        return $value;
    }
}

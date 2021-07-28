<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Perm
 */


namespace Amasty\Perm\Plugin;

use Amasty\Perm\Helper\Data as PermHelper;
use Magento\Customer\Model\Customer\Attribute\Source\Group;

class CustomerAttributeSourceGroup
{
    /**
     * @var PermHelper
     */
    protected $permHelper;

    public function __construct(
        PermHelper $permHelper
    ) {
        $this->permHelper = $permHelper;
    }

    /**
     * @param Group $group
     * @param array $options
     *
     * @return array
     */
    public function afterGetAllOptions(
        Group $group,
        $options
    ) {
        if ($this->permHelper->isBackendDealer()) {
            $dealerGroups = $this->permHelper->getBackendDealer()->getCustomerGroups();

            if (count($dealerGroups) > 0) {
                if (!empty($options[0]) && is_array($options[0]['value'])) {
                    $newOptions = $this->prepareOptions($options[0]['value'], $dealerGroups);
                    $options[0]['value'] = $newOptions;
                } else {
                    $newOptions = $this->prepareOptions($options, $dealerGroups);
                    $options = $newOptions;
                }
            }
        }

        return $options;
    }

    /**
     * @param array $options
     * @param array $dealerGroups
     *
     * @return array
     */
    private function prepareOptions($options, $dealerGroups)
    {
        $newOptions = [];

        foreach ($options as $option) {
            if (in_array($option['value'], $dealerGroups)) {
                $newOptions[] = $option;
            }
        }

        return $newOptions;
    }
}

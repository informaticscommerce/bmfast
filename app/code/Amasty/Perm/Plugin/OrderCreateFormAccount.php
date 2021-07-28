<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Perm
 */


namespace Amasty\Perm\Plugin;

use Amasty\Perm\Helper\Data as PermHelper;
use Magento\Framework\Data\Form as DataForm;
use Magento\Sales\Block\Adminhtml\Order\Create\Form\Account as FormAccount;

class OrderCreateFormAccount
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
     * @param FormAccount $formAccount
     * @param DataForm    $dataForm
     *
     * @return DataForm
     */
    public function afterGetForm(
        FormAccount $formAccount,
        DataForm $dataForm
    ) {
        if ($this->permHelper->isBackendDealer()) {
            $groups = $this->permHelper->getBackendDealer()->getCustomerGroups();

            if (count($groups) > 0) {
                foreach ($dataForm->getElements() as $fieldset) {
                    if ($fieldset instanceof \Magento\Framework\Data\Form\Element\Fieldset) {
                        $groupsElement = $fieldset->getElements()->searchById('group_id');
                        $values = $groupsElement->getValues();

                        if (!empty($values[0]) && is_array($values[0]['value'])) {
                            $newOptions = $this->prepareOptions($values[0]['value'], $groups);
                            $values[0]['value'] = $newOptions;
                        } else {
                            $newOptions = $this->prepareOptions($values, $groups);
                            $values = $newOptions;
                        }

                        $groupsElement->setValues($values);
                    }
                }
            }
        }

        return $dataForm;
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

        foreach ($options as $key => $value) {
            if (in_array($value['value'], $dealerGroups)) {
                $newOptions[$key] = $value;
            }
        }

        return $newOptions;
    }
}

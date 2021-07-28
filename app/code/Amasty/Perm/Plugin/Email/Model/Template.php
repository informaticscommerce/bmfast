<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Perm
 */


declare(strict_types=1);

namespace Amasty\Perm\Plugin\Email\Model;

use Magento\Email\Model\Template as ModelTemplate;

class Template
{
    const IS_SALES_EMAIL_VARIABLE = '{{var order.increment_id}}';

    /**
     * Plugin for adding variable to marketing order emails to Insert Variable.
     *
     * @param \Magento\Framework\Mail\TemplateInterface|\Magento\Email\Model\Template $subject
     * @param array $result
     * @return array $result
     */
    public function afterGetVariablesOptionArray(ModelTemplate $subject, array $result): array
    {
        if (!empty($result['value']) && $this->isSalesEmail($result)) {
            $result['value'][] = [
                'label' => __('Dealer Contact Name'),
                'value' => '{{var order.order_dealer.contactname}}'
            ];
        }

        return $result;
    }

    /**
     * @param array $result
     * @return bool
     */
    private function isSalesEmail(array $result): bool
    {
        foreach ($result['value'] as $variable) {
            if ($variable['value'] === self::IS_SALES_EMAIL_VARIABLE) {

                return true;
            }
        }

        return false;
    }
}

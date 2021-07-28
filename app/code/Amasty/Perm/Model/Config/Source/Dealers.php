<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Perm
 */


namespace Amasty\Perm\Model\Config\Source;

use Amasty\Perm\Helper\Data as PermHelper;
use Amasty\Perm\Model\Mailer;
use Amasty\Perm\Model\ResourceModel\Dealer\CollectionFactory;

class Dealers implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var PermHelper
     */
    protected $permHelper;

    public function __construct(
        CollectionFactory $collectionFactory,
        PermHelper $permHelper
    ) {
        $this->permHelper = $permHelper;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray($withAdmin = false)
    {
        $optionArray = [];
        $allowedDealers = [];

        if ($this->permHelper->isBackendDealer()
            && !$this->permHelper->isAllowAllCustomersAndOrders()
        ) {
            $allowedDealers[] = $this->permHelper->getBackendDealer()->getId();
        }

        $arr = $this->toArray($withAdmin, $allowedDealers);

        foreach ($arr as $value => $label) {
            $optionArray[] = [
                'value' => $value,
                'label' => $label
            ];
        }

        return $optionArray;
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray($withAdmin = false, array $allowedDealers = [])
    {
        $collection = $this->collectionFactory->create()->addUserData();
        $options = [];

        if ($withAdmin) {
            $options[] = __($this->permHelper->getScopeValue(Mailer::SCOPE_MESSAGES_ADMIN_NAME));
        }

        foreach ($collection as $dealer) {
            if (count($allowedDealers) === 0
                || in_array($dealer->getId(), $allowedDealers)
            ) {
                $options[$dealer->getId()] = $dealer->getContactname();
            }
        }

        return $options;
    }
}

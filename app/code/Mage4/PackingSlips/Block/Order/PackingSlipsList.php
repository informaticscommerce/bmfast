<?php
/**
 * User: Amit Bera
 * Email: dev.amitbera@gmail.com
 */

namespace Mage4\PackingSlips\Block\Order;

use Magento\Framework\View\Element\Template;

class PackingSlipsList  extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactory
     */
    protected $orderCollectionFactory;
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    public function __construct (
        Template\Context $context,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Magento\Customer\Model\Session $customerSession,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->customerSession = $customerSession;
    }
    public function getPackingSlipsList()
    {
        $orders =  $this->orderCollectionFactory->create()->addAttributeToSelect(
            '*'
        )->addAttributeToFilter(
            'customer_id',
            $this->customerSession->getCustomerId()
        );

        $sales_shipment_track_table = $orders->getTable("sales_shipment_track");
        $orders->join(
            array('ordId' => $sales_shipment_track_table),
            'ordId.order_id = main_table.entity_id',
            []
        );
        $orders->addFieldToFilter('track_number', ['neq' => null]);
        return $orders->distinct(true);
    }
    public function getViewTracksUrl($orderId)
    {
        return $this->getUrl('sales/order/shipment', ['order_id' => $orderId]);
    }
}

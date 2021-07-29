<?php
/**
 * User: Amit Bera
 * Email: dev.amitbera@gmail.com
 */

namespace Mage4\Invoices\Block\Order;


use Magento\Framework\View\Element\Template;

class InvoiceList  extends \Magento\Framework\View\Element\Template
{
    protected  $invoices;
    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactory
     */
    protected $orderCollectionFactory;
    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\Invoice\CollectionFactory
     */
    protected $invoiceCollectionFactory;
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    public function __construct(
        Template\Context $context,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Magento\Sales\Model\ResourceModel\Order\Invoice\CollectionFactory $invoiceCollectionFactory ,
        \Magento\Customer\Model\Session $customerSession,
        array $data = [])
    {
        parent::__construct($context, $data);
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->invoiceCollectionFactory = $invoiceCollectionFactory;
        $this->customerSession = $customerSession;
    }
    public function getInvoiceList()
    {
        $orders =  $this->orderCollectionFactory->create()->addAttributeToSelect(
            '*'
        )->addAttributeToFilter(
            'customer_id',
            $this->customerSession->getCustomerId()
        );
        $orderids = $orders->getColumnValues('entity_id');
        if(count($orderids))
        {
            $this->invoices = $this->invoiceCollectionFactory->create()
                ->addFieldToSelect('*')->addFieldToFilter('order_id',['in' => $orderids]);
        }
        return $this->invoices;
    }
    public function getPrintInvoiceUrl($invoice)
    {
        return $this->getUrl('sales/order/printInvoice', ['invoice_id' => $invoice->getId()]);
    }
    public function getViewInvoiceUrl($orderId)
    {
        return $this->getUrl('sales/order/view', ['order_id' => $orderId]);
    }
}

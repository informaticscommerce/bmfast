<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Mage4\ExtendMagentoSales\Block\Order;

use \Magento\Framework\App\ObjectManager;
use \Magento\Sales\Model\ResourceModel\Order\CollectionFactoryInterface;

/**
 * Sales order history block
 *
 * @api
 * @since 100.0.2
 */
class History extends \Magento\Sales\Block\Order\History
{
    /**
     * @var string
     */
    protected $_template = 'Magento_Sales::order/history.phtml';

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactory
     */
    protected $_orderCollectionFactory;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @var \Magento\Sales\Model\Order\Config
     */
    protected $_orderConfig;

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\Collection
     */
    protected $orders;

    /**
     * @var CollectionFactoryInterface
     */
    private $orderCollectionFactory;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Sales\Model\Order\Config $orderConfig
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Sales\Model\Order\Config $orderConfig,
        array $data = []
    ) {
        parent::__construct($context, $orderCollectionFactory, $customerSession, $orderConfig, $data);
    }

    /**
     * Provide order collection factory
     *
     * @return CollectionFactoryInterface
     * @deprecated 100.1.1
     */
    private function getOrderCollectionFactory()
    {
        if ($this->orderCollectionFactory === null) {
            $this->orderCollectionFactory = ObjectManager::getInstance()->get(CollectionFactoryInterface::class);
        }
        return $this->orderCollectionFactory;
    }

    /**
     * Get customer orders
     *
     * @return bool|\Magento\Sales\Model\ResourceModel\Order\Collection
     */
    public function getOrderlist()
    {
        if (!($customerId = $this->_customerSession->getCustomerId())) {
            return false;
        }
        if (!$this->orders) {
            $orderlist = $this->getOrderActive($customerId);
        } else {
            $orderlist = $this->getOrderFilterList($customerId);
        }
        $page = $this->getRequest()->getParam('p') ?: 1;
        $num = $this->getRequest()->getParam('limit') ?: 10;
        $orderlist->setPage($page, $num);
        return $orderlist->distinct(true);
    }

    private function getOrderActive($customerId){

        $this->orders = $this->getOrderCollectionFactory()->create($customerId)->addFieldToSelect(
            '*'
        )->addFieldToFilter(
            'status',
            ['in' => $this->_orderConfig->getVisibleOnFrontStatuses()]
        )->setOrder(
            'created_at',
            'desc'
        );

        return $this->orders;
    }

    private function getOrderFilterList($customerId)
    {
        $this->orders = $this->getOrderCollectionFactory()->create($customerId);
        $post=$this->getRequest()->getParams();

        if (isset($post)) {
            if (!empty($post['customer-name'])) {
                $customer_name  = trim($post['customer-name']," ");
                $customerArr    = explode(" ",$customer_name);

                if (isset($customerArr[0]) && !isset($customerArr[1])) {
                    $this->orders->addFieldToFilter(
                        ['customer_firstname','customer_lastname'],
                        [
                            ['like' => '%'.$customerArr[0].'%'],
                            ['like' => '%'.$customerArr[0].'%']
                        ]
                    );
                }
                if (isset($customerArr[0]) && isset($customerArr[1])) {
                    $this->orders->addFieldToFilter(
                        'customer_firstname',
                        ['like' => '%'.$customerArr[0].'%']
                    );
                    $this->orders->addFieldToFilter(
                        'customer_lastname',
                        ['like' => '%'.$customerArr[1].'%']
                    );
                }
            }

            if (!empty($post['date-start']) && !empty($post['date-end'])) {
                $date=['from' =>date("Y-m-d H:i:s",strtotime( $post['date-start'].' 00:00:00')),'to'=>date("Y-m-d H:i:s",strtotime( $post['date-end'].' 24:00:00'))];
                $this->orders->addFieldToFilter(
                    'main_table.created_at',
                    $date
                );
            } else if (!empty($post['date-start'])) {
                $date =['from' =>date("Y-m-d H:i:s",strtotime( $post['date-start'].' 00:00:00')),'to'=>date("Y-m-d H:i:s")];
                $this->orders->addFieldToFilter(
                    'main_table.created_at',
                    $date
                );

            } else if (!empty($post['date-end'])) {
                $date =['from' =>date("Y-m-d H:i:s",strtotime( '1980-05-10 12:00')),'to' =>date("Y-m-d H:i:s",strtotime($post['date-end'].' 23:59:59'))];
                $this->orders->addFieldToFilter(
                    'main_table.created_at',
                    $date
                );
            }

            if (!empty($post['po-num'])) {
                $sales_order_payment_table = $this->orders->getTable("sales_order_payment");
                $this->orders->join(
                    array('payment' => $sales_order_payment_table),
                    'payment.parent_id = main_table.entity_id',
                    array()
                );
                $this->orders->addFieldToFilter('method', 'purchaseorder');
                $this->orders->addFieldToFilter('po_number', array('like' => '%' . trim($post['po-num']," ") . '%'))->distinct(true);
            }

            if (!empty($post['search-keyword'])) {
                $search_keyword  = trim($post['search-keyword']," ");
                $search_keyArr    = explode(" ",$search_keyword);
                $sales_order_payment_table = $this->orders->getTable("sales_order_payment");
                $sales_order_grid_table = $this->orders->getTable("sales_order_grid");
                $this->orders->getSelect()->join(
                    array('oat' => $sales_order_grid_table),
                    'oat.entity_id = main_table.entity_id',
                    []
                );

                if (isset($search_keyArr[0]) && !isset($search_keyArr[1])) {
                    $this->orders->join(
                        array('payment' => $sales_order_payment_table),
                        'payment.parent_id = main_table.entity_id',
                        []
                    );

                    $this->orders->addFieldToFilter(
                        ['entity_id','customer_firstname','customer_lastname','po_number', 'shipping_name'],
                        [
                            ['like' => '%'.$search_keyArr[0].'%'],
                            ['like' => '%'.$search_keyArr[0].'%'],
                            ['like' => '%'.$search_keyArr[0].'%'],
                            ['like' => '%'.$search_keyArr[0].'%'],
                            ['like' => '%'.$search_keyArr[0].'%']
                        ]
                    );
                } else {
                    $this->orders->addFieldToFilter(
                        ['customer_firstname', 'shipping_name'],
                        [
                            ['like' => '%'.$search_keyArr[0].'%'],
                            ['like' => '%'.$search_keyword.'%']
                        ]
                    );
                    $this->orders->addFieldToFilter(
                        ['customer_lastname', 'shipping_name'],
                        [
                            ['like' => '%'.$search_keyArr[1].'%'],
                            ['like' => '%'.$search_keyword.'%']
                        ]
                    );
                }
            }

            $this->orders->setOrder(
                'created_at',
                'desc'
            );

            if (!empty($post['sort-order'])) {
                if (($post['sort-order']) === 'created-asc') {
                    $this->orders->setOrder(
                        'created_at',
                        'asc'
                    );
                }
            }

        }

        return $this->orders->distinct(true);;
    }

    /**
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if ($this->getOrderlist()) {
            $pager = $this->getLayout()->createBlock(
                \Magento\Theme\Block\Html\Pager::class,
                'sales.order.history.pagersearch'
            )->setCollection(
                $this->getOrderlist()
            );
            $this->setChild('pager', $pager);
            $this->getOrderlist()->load();
        }
        return $this;
    }
}

<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Perm
 */


namespace Amasty\Perm\Controller\Adminhtml\Order;

use Amasty\Perm\Model\DealerOrder;
use Amasty\Perm\Model\DealerOrder\AssignHistory;
use Amasty\Perm\Model\Mailer;
use Magento\Sales\Controller\Adminhtml\Order;

class AddDealerComment extends Order
{
    /**
     * @var null|DealerOrder
     */
    protected $dealerOrder;

    /**
     * @param $orderId
     *
     * @return mixed
     */
    protected function initDealerOrder($orderId)
    {
        if ($this->dealerOrder === null) {
            $this->dealerOrder = $this->_objectManager->create(DealerOrder::class)->load($orderId, 'order_id');

            if (!$this->dealerOrder->getId()) {
                $this->dealerOrder->setOrderId($orderId);
            }
        }

        return $this->dealerOrder;
    }

    /**
     * @return $this|\Magento\Framework\Controller\Result\Json|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $order = $this->_initOrder();

        if ($order) {
            try {
                $data = $this->getRequest()->getPost('amasty_perm_order_dealer_comment_history');
                /** @var  DealerOrder $dealerOrder */
                $dealerOrder = $this->initDealerOrder($order->getId());
                $emailsList = $dealerOrder->getDealer(true)->getAllEmailsWithName();

                if (empty($data['comment']) && $data['dealer'] == $dealerOrder->getDealerId()) {
                    throw new \Magento\Framework\Exception\LocalizedException(__('Please enter a comment.'));
                }

                $notify = isset($data['is_dealer_notified']) ? $data['is_dealer_notified'] : false;
                /** @var AssignHistory $history */
                $history = $dealerOrder->addDealerHistoryComment($data['comment'], $order->getId(), $data['dealer']);
                $history->setIsDealerNotified($notify);
                $history->save();
                $comment = trim(strip_tags($data['comment']));
                $dealerOrder->save();
                $vars = [
                    'order' => $order,
                    'comment' => $comment,
                    'billing' => $order->getBillingAddress(),
                    'store' => $order->getStore(),
                    'history' => $history,
                    'dealerOrder' => $dealerOrder
                ];

                if ($notify) {
                    if ($history->isDealerChanged()) {
                        $emailsList = $dealerOrder->getDealer(true)->getAllEmailsWithName();
                    }

                    $this->_objectManager->create(Mailer::class)
                        ->send(
                            $order->getStoreId(),
                            $emailsList,
                            $vars
                        );
                }

                return $this->resultPageFactory->create();
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $response = ['error' => true, 'message' => $e->getMessage()];
            } catch (\Exception $e) {
                $response = ['error' => true, 'message' => $e->getMessage() . __('We cannot assign dealer.')];
            }

            if (is_array($response)) {
                $resultJson = $this->resultJsonFactory->create();
                $resultJson->setData($response);

                return $resultJson;
            }
        }

        return $this->resultRedirectFactory->create()->setPath('sales/*/');
    }
}

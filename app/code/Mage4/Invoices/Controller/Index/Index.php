<?php
namespace Mage4\Invoices\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action
{
    public function execute()
    {
//        echo "ggggg";exit;
        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }
}

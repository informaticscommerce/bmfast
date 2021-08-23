<?php

namespace Mage4\Certification\Block\Order;

use Magento\Framework\View\Element\Template;

class CertificationList  extends \Magento\Framework\View\Element\Template
{
    protected $_storeManager;
    protected $_urlInterface;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\UrlInterface $urlInterface
    )
    {
        $this->_storeManager = $storeManager;
        $this->_urlInterface = $urlInterface;
    }

    /**
     * Printing URLs using StoreManagerInterface
     */
    public function getBaseUrlByStoreManager()
    {
        // by default: URL_TYPE_LINK is returned
        return $this->_storeManager->getStore()->getBaseUrl();
    }
}

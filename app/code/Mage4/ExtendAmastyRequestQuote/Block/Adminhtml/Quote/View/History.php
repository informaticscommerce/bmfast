<?php

namespace Mage4\ExtendAmastyRequestQuote\Block\Adminhtml\Quote\View;

class History extends \Magento\Backend\Block\Template
{
    /**
     * @var \Amasty\RequestQuote\Model\Quote\Backend\Session
     */
    private $quoteSession;

    /**
     * @var \Magento\Framework\DataObjectFactory
     */
    private $dataObjectFactory;

    /**
     * @var \Amasty\Base\Model\Serializer
     */
    private $serializer;

    protected $storeManager;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Amasty\RequestQuote\Model\Quote\Backend\Session $quoteSession,
        \Magento\Framework\DataObjectFactory $dataObjectFactory,
        \Amasty\Base\Model\Serializer $serializer,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->quoteSession = $quoteSession;
        $this->dataObjectFactory = $dataObjectFactory;
        $this->serializer = $serializer;
        $this->storeManager = $storeManager;
    }

    /**
     * @return \Amasty\RequestQuote\Api\Data\QuoteInterface
     */
    public function getQuote()
    {
        return $this->quoteSession->getQuote();
    }

    /**
     * @return \Amasty\RequestQuote\Model\Quote\Backend\Session
     */
    public function getQuoteSession()
    {
        return $this->quoteSession;
    }

    /**
     * Check allow to add comment
     *
     * @return bool
     */
    public function canAddComment()
    {
        return !$this->getNotes()->getAdminNote();
    }

    /**
     * @return \Magento\Framework\DataObject
     */
    public function getNotes()
    {
        if (!$this->getData('notes')) {
            if ($remarks = $this->getQuote()->getRemarks()) {
                $remarks = $this->serializer->unserialize($remarks);
                $this->setData('notes', $this->dataObjectFactory->create(['data' => $remarks]));
            } else {
                $this->setData('notes', $this->dataObjectFactory->create());
            }
        }
        return $this->getData('notes');
    }

    public function getFile()
    {
        return $this->getQuote()->getCustomFile();
    }

    public function getMediaUrl()
    {
        return $mediaUrl = $this->storeManager
            ->getStore()
            ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

    }
}

<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_RequestQuote
 */


declare(strict_types=1);

namespace Amasty\RequestQuote\Controller\Adminhtml\Quote;

use Amasty\RequestQuote\Api\QuoteRepositoryInterface;
use Amasty\RequestQuote\Model\Pdf\ComponentChecker;
use Amasty\RequestQuote\Model\Pdf\PdfProvider;
use Amasty\RequestQuote\Model\Registry;
use Amasty\RequestQuote\Model\RegistryConstants;
use Magento\Backend\App\Action\Context;
use Psr\Log\LoggerInterface;

class Pdf extends \Magento\Backend\App\Action
{
    const ADMIN_RESOURCE = 'Amasty_RequestQuote::pdfDownload';

    /**
     * @var PdfProvider
     */
    private $pdfProvider;

    /**
     * @var ComponentChecker
     */
    private $componentChecker;

    /**
     * @var QuoteRepositoryInterface
     */
    private $quoteRepository;

    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        Context $context,
        PdfProvider $pdfProvider,
        ComponentChecker $componentChecker,
        LoggerInterface $logger,
        QuoteRepositoryInterface $quoteRepository,
        Registry $registry
    ) {
        $this->pdfProvider = $pdfProvider;
        $this->componentChecker = $componentChecker;
        $this->quoteRepository = $quoteRepository;
        $this->registry = $registry;
        $this->logger = $logger;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Raw
     */
    public function execute()
    {
        if (!$this->componentChecker->isComponentsExist()) {
            $this->messageManager->addErrorMessage($this->componentChecker->getComponentsErrorMessage());

            return $this->_redirect('*/*/');
        }

        try {
            $quoteId = (int)$this->_request->getParam('quote_id');
            $quote = $this->quoteRepository->get($quoteId, ['*']);
            $this->registry->register(RegistryConstants::AMASTY_QUOTE, $quote);
            $this->_view->loadLayout();

            return $this->pdfProvider->getRawPdf($quote);
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('An error occurred. The PDF was not downloaded.'));
            $this->logger->error($e->getMessage());
            $this->_redirect('*/*/');
        }
    }
}

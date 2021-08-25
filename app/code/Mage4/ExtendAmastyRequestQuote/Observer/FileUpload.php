<?php

namespace Mage4\ExtendAmastyRequestQuote\Observer;

use Amasty\RequestQuote\Api\Data\QuoteInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\UploaderFactory;

class FileUpload implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * Uploadfactory
     *
     * @var ResourceConnection
     */
    protected $resource;

    /**
     * Uploadfactory
     *
     * @var UploaderFactory
     */
    protected $uploaderFactory;

    /**
     * Filesystem
     *
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * Initialize
     *
     * @param ResourceConnection $resource Initialize resource
     * @param UploaderFactory $uploaderFactory Initialize uploadfactory
     * @param Filesystem $filesystem Initialize  filesystem
     */
    public function __construct(
        ResourceConnection $resource,
        UploaderFactory $uploaderFactory,
        Filesystem $filesystem
    )
    {
        $this->resource = $resource;
        $this->uploaderFactory = $uploaderFactory;
        $this->filesystem = $filesystem;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $quote = $observer->getQuote();

        try {
            if ($_FILES['quote_file']['name']) {
                $mediaUrl = $this->filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath('mage4/requestquote/');

                $uploader = $this->uploaderFactory->create(['fileId' => 'quote_file']);
                $uploader->setAllowedExtensions(['pdf', 'doc', 'docs', 'csv', 'txt']);
                $uploader->setAllowRenameFiles(true);
                $uploader->setFilesDispersion(false);
                $result = $uploader->save($mediaUrl);
                $quote->setCustomFile($result['file']);
                $this->saveAmastyQuote($quote);
            }
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Something went wrong'));
            $this->getLogger()->error($e->getMessage());
        }
    }

    /**
     * @param \Magento\Quote\Model\Quote $quote
     */
    private function saveAmastyQuote(\Magento\Quote\Model\Quote $quote)
    {
        $connection = $this->resource->getConnection();
        $connection->query(sprintf('update amasty_quote set custom_file=\'%s\' where quote_id = %s', $quote->getCustomFile(), $quote->getId()));
    }
}

<?php

namespace Mage4\ExtendAmastyRequestQuote\Observer;

use Exception;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Quote\Model\Quote;

class FileUpload implements ObserverInterface
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

    public function execute(Observer $observer)
    {
        $quote = $observer->getQuote();

        try {
            if ($_FILES['quote_file']['name']) {
                $mediaUrl = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath('informaticscommerce/requestquote/');

                $uploader = $this->uploaderFactory->create(['fileId' => 'quote_file']);
                $uploader->setAllowedExtensions(['pdf', 'doc', 'docs', 'csv', 'txt']);
                $uploader->setAllowRenameFiles(true);
                $uploader->setFilesDispersion(false);
                $result = $uploader->save($mediaUrl);
                $quote->setCustomFile($result['file']);
                $this->saveAmastyQuote($quote);
            }
        } catch (LocalizedException $e) {
            $connection = $this->resource->getConnection();
            $connection->query(sprintf('DELETE FROM amasty_quote WHERE quote_id = %s', $quote->getId()));
            throw new CouldNotDeleteException(__("Unable to submit. " . $e->getMessage()));
        } catch (Exception $e) {
            throw new CouldNotDeleteException(__("Unable to submit. " . $e->getMessage()));
            $this->getLogger()->error($e->getMessage());
        }
    }

    /**
     * @param Quote $quote
     */
    private function saveAmastyQuote(Quote $quote)
    {
        $connection = $this->resource->getConnection();
        if (!empty($quote->getCustomFile())) {
            $connection->query(sprintf('update amasty_quote set custom_file=\'%s\' where quote_id = %s', $quote->getCustomFile(), $quote->getId()));
        }
    }
}

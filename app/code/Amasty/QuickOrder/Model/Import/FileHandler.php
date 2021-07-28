<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


declare(strict_types=1);

namespace Amasty\QuickOrder\Model\Import;

use Amasty\QuickOrder\Api\Source\SourceReaderInterface;
use Amasty\QuickOrder\Model\Import\Source\SourceReaderAdapter;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem\Io\File;

class FileHandler
{
    const SUPPORTED_FORMATS = [
        'csv',
        'xml'
    ];

    /**
     * @var SourceReaderAdapter
     */
    private $sourceReaderAdapter;

    /**
     * @var ImportHandler
     */
    private $importHandler;

    /**
     * @var File
     */
    private $ioFile;

    /**
     * @var SourceReaderInterface
     */
    private $sourceReader;

    public function __construct(
        SourceReaderAdapter $sourceReaderAdapter,
        ImportHandler $importHandler,
        File $ioFile
    ) {
        $this->sourceReaderAdapter = $sourceReaderAdapter;
        $this->importHandler = $importHandler;
        $this->ioFile = $ioFile;
    }

    /**
     * @param array $fileInfo
     * @throws FileSystemException
     * @throws LocalizedException
     */
    private function initReader(array $fileInfo)
    {
        $fileFormat = $this->getFileExtension($fileInfo['name']);
        if (!in_array($fileFormat, static::SUPPORTED_FORMATS)) {
            throw new LocalizedException(__('File format not supported.'));
        }
        $this->sourceReader = $this->sourceReaderAdapter->getReader($fileFormat, $fileInfo['tmp_name']);
    }

    /**
     * @param array $fileInfo
     * @return array
     * @throws FileSystemException
     * @throws LocalizedException
     */
    public function import(array $fileInfo): array
    {
        $this->initReader($fileInfo);

        return $this->importHandler->execute($this->sourceReader);
    }

    /**
     * @param array $fileInfo
     * @return int
     * @throws FileSystemException
     * @throws LocalizedException
     */
    public function calculateTotalQty(array $fileInfo): int
    {
        $this->initReader($fileInfo);

        return $this->sourceReader->estimateRecordsCount();
    }

    /**
     * @param string $fileName
     * @return string
     */
    private function getFileExtension(string $fileName): string
    {
        return $this->ioFile->getPathInfo($fileName)['extension'] ?? '';
    }
}

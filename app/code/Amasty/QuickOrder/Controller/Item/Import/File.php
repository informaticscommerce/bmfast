<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


declare(strict_types=1);

namespace Amasty\QuickOrder\Controller\Item\Import;

use Amasty\QuickOrder\Model\Import\FileHandler;
use Exception;
use Magento\Framework\Exception\LocalizedException;

class File extends AbstractAction
{
    const INPUT_NAME = 'multiple_file';

    /**
     * @return array
     * @throws LocalizedException
     * @throws Exception
     */
    public function importAction(): array
    {
        if ($fileInfo = $this->getFileInfo()) {
            return $this->getFileHandler()->import($fileInfo);
        } else {
            throw new LocalizedException(__('File info not provided.'));
        }
    }

    /**
     * @return FileHandler|null
     * @throws Exception
     */
    private function getFileHandler()
    {
        return $this->getData('fileHandler');
    }

    /**
     * @return array|null
     */
    protected function getFileInfo()
    {
        $fileInfo = null;
        if ($files = $this->getRequest()->getFiles()) {
            $fileInfo = $files[static::INPUT_NAME] ?? null;
        }

        return $fileInfo;
    }

    /**
     * @return int
     * @throws LocalizedException
     * @throws Exception
     */
    public function calculateTotalQty(): int
    {
        if ($fileInfo = $this->getFileInfo()) {
            return $this->getFileHandler()->calculateTotalQty($fileInfo);
        } else {
            throw new LocalizedException(__('File info not provided.'));
        }
    }
}

<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_RequestQuote
 */


declare(strict_types=1);

namespace Amasty\RequestQuote\Model\Pdf;

use Magento\Framework\Phrase;

class ComponentChecker
{
    public function isComponentsExist(): bool
    {
        try {
            $classExists = class_exists(\Dompdf\Dompdf::class);
        } catch (\Exception $e) {
            $classExists = false;
        }

        return $classExists;
    }

    public function getComponentsErrorMessage(): Phrase
    {
        return __(
            'To use PDF customizer, please install the library dompdf/dompdf since it is required for proper '
            . 'PDF customizer functioning. To do this, run the command '
            . '"composer require dompdf/dompdf" in the main site folder.'
        );
    }
}

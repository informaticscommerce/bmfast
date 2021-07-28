<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_RequestQuote
 */


declare(strict_types=1);

namespace Amasty\RequestQuote\Model\Quote\Item;

/**
 * use physical class instead of virtual type because plugin on virtual type caused fatal
 * phpcs:ignoreFile
 */
class Processor extends \Magento\Quote\Model\Quote\Item\Processor
{
}

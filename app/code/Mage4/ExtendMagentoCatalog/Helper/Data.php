<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Mage4\ExtendMagentoCatalog\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * Catalog Data helper
 *
 */
class Data extends AbstractHelper implements ArgumentInterface
{
    public function __construct(
        Context $context
    )
    {
        parent::__construct($context);
    }

}

<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


declare(strict_types=1);

namespace Amasty\QuickOrder\Block\Grid;

use Amasty\QuickOrder\Model\ConfigProvider;
use Magento\Customer\Model\Context as CustomerContext;
use Magento\Framework\App\Http\Context as HttpContext;

class FileConfigProcessor implements LayoutProcessorInterface
{
    /**
     * @var ConfigProvider
     */
    private $configProvider;

    /**
     * @var HttpContext
     */
    private $httpContext;

    public function __construct(
        ConfigProvider $configProvider,
        HttpContext $httpContext
    ) {
        $this->configProvider = $configProvider;
        $this->httpContext = $httpContext;
    }

    public function process($jsLayout): array
    {
        if (isset($jsLayout['components']['multiple']['config'])) {
            $jsLayout['components']['multiple']['config']['fileUploadEnabled']
                = $this->configProvider->isCustomerCanUploadFile(
                    (int)$this->httpContext->getValue(CustomerContext::CONTEXT_GROUP)
                );
        }

        return $jsLayout;
    }
}

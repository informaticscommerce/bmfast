<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_QuickOrder
 */


declare(strict_types=1);

namespace Amasty\QuickOrder\Block;

use Amasty\QuickOrder\Model\ConfigProvider;
use Amasty\QuickOrder\Model\GetIsAvailable;
use Magento\Framework\Phrase;
use Magento\Framework\View\Element\Html\Link as HtmlLink;
use Magento\Framework\View\Element\Template;

class Link extends HtmlLink
{
    /**
     * @var ConfigProvider
     */
    private $configProvider;

    /**
     * @var GetIsAvailable
     */
    private $getIsAvailable;

    public function __construct(
        ConfigProvider $configProvider,
        GetIsAvailable $getIsAvailable,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->configProvider = $configProvider;
        $this->getIsAvailable = $getIsAvailable;
    }

    /**
     * @return Phrase|string
     */
    public function getLabel()
    {
        return $this->configProvider->getLabel();
    }

    /**
     * @return string
     */
    public function getHref(): string
    {
        return (string) $this->_urlBuilder->getUrl($this->configProvider->getUrlKey());
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        $result = '';
        if ($this->isShowLink()) {
            $result = parent::_toHtml();
        }

        return $result;
    }

    /**
     * @return bool
     */
    private function isShowLink(): bool
    {
        return isset($this->_data['position'])
            && $this->configProvider->isDisplayLInkIn($this->_data['position'])
            && $this->getIsAvailable->execute();
    }
}

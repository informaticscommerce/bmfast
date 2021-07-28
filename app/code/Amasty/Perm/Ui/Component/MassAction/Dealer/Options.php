<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Perm
 */


namespace Amasty\Perm\Ui\Component\MassAction\Dealer;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Amasty\Perm\Model\ResourceModel\Dealer\CollectionFactory;

class Options extends \Magento\Ui\Component\Action
{
    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @var OptionSourceInterface
     */
    private $optionSource;

    public function __construct(
        ContextInterface $context,
        UrlInterface $urlBuilder,
        OptionSourceInterface $optionSource = null,
        array $components = [],
        array $data = [],
        $actions = null
    ) {
        parent::__construct($context, $components, $data, $actions);
        $this->urlBuilder = $urlBuilder;
        $this->optionSource = $optionSource;
    }

    /**
     * Prepare params array for urlBuilder
     *
     * @param string|int $optionValue
     *
     * @return array
     */
    public function getUrlParams($optionValue)
    {
        return ['dealer' => $optionValue];
    }

    /**
     * Complete Mass actions with external options
     */
    public function prepare()
    {
        $options = $this->optionSource->toOptionArray();
        foreach ($options as $option) {
            $this->actions[] = [
                'type'    => strtolower($option['label']),
                'label'   => $option['label'],
                'url'     => $this->urlBuilder->getUrl(
                    $this->_data['config']['massActionUrl'],
                    $this->getUrlParams($option['value'])
                ),
                'confirm' => $this->_data['config']['confirm']
            ];
        }
        parent::prepare();
    }
}

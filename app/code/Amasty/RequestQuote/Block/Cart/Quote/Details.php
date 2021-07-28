<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_RequestQuote
 */


namespace Amasty\RequestQuote\Block\Cart\Quote;

use Amasty\RequestQuote\Plugin\CustomerCustomAttributes\Helper\DataPlugin;
use Magento\Customer\Model\Form as CustomerForm;
use Magento\Customer\Model\FormFactory as CustomerFormFactory;
use Magento\Eav\Model\Attribute;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Details extends Template
{
    const CUSTOMER_ACCOUNT_CREATE = 'customer_account_create';

    const ADDITIONAL_ATTRIBUTES = [
        'prefix',
        'firstname',
        'middlename',
        'lastname',
        'suffix',
        'dob',
        'taxvat',
        'gender'
    ];

    /**
     * @var CustomerForm
     */
    private $customerFormFactory;

    /**
     * @var array
     */
    private $forms = [];

    public function __construct(
        CustomerFormFactory $customerFormFactory,
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->customerFormFactory = $customerFormFactory;
    }

    /**
     * @return string
     */
    public function getJsLayout()
    {
        if (isset($this->jsLayout['components']['details']['config'])) {
            $attributesConfig = [];

            $this->populateAttributesConfig($attributesConfig, $this->getAdditionalAttributes());
            $this->populateAttributesConfig($attributesConfig, $this->getAccountForm()->getUserAttributes());
            $this->populateAttributesConfig($attributesConfig, $this->getQuoteForm()->getUserAttributes(), true);

            $this->jsLayout['components']['details']['children']['dateRenderer']['config']['options']['dateFormat']
                = $this->getDateFormat();

            $this->jsLayout['components']['details']['config']['attributes'] = array_values($attributesConfig);
        }

        return json_encode($this->jsLayout, JSON_HEX_TAG);
    }

    /**
     * @param array $attributesConfig
     * @param array $attributes
     * @param bool $notRequired
     */
    private function populateAttributesConfig(array &$attributesConfig, array $attributes, bool $notRequired = false)
    {
        /** @var Attribute $attribute */
        foreach ($attributes as $attribute) {
            if ($attribute
                && !isset($attributesConfig[$attribute->getAttributeCode()])
                && $attribute->getIsVisible()
                && ($notRequired || $attribute->getIsRequired())
            ) {
                try {
                    $attributesConfig[$attribute->getAttributeCode()] = $this->getAttributeConfig($attribute);
                } catch (LocalizedException $e) {
                    $this->_logger->error($e->getMessage());
                }
            }
        }
    }

    /**
     * @param Attribute $attribute
     * @return array
     * @throws LocalizedException
     */
    private function getAttributeConfig(Attribute $attribute): array
    {
        switch ($attribute->getFrontendInput()) {
            case 'date':
                $defaultValue = $attribute->getDefaultValue()
                    ? $this->_localeDate->formatDateTime(
                        $attribute->getDefaultValue(),
                        \IntlDateFormatter::SHORT,
                        \IntlDateFormatter::NONE,
                        null,
                        'UTC',
                        $this->getDateFormat()
                    )
                    : '';
                break;
            default:
                $defaultValue = $attribute->getDefaultValue();
        }

        return [
            'type' => $attribute->getFrontendInput(),
            'label' => __($attribute->getStoreLabel()),
            'required' => (bool) $attribute->getIsRequired(),
            'code' => $attribute->getAttributeCode(),
            'options' => $attribute->usesSource() ? $attribute->getSource()->getAllOptions() : [],
            'lineCount' => $attribute->getMultilineCount() - 1,
            'defaultValue' => $defaultValue ?: ''
        ];
    }

    /**
     * @return array
     */
    private function getAdditionalAttributes(): array
    {
        return array_map(
            function ($attributeCode) {
                return $this->getAttribute($attributeCode);
            },
            self::ADDITIONAL_ATTRIBUTES
        );
    }

    /**
     * @param string $attributeCode
     * @return bool|Attribute
     */
    private function getAttribute(string $attributeCode)
    {
        return $this->getAccountForm()->setFormCode(self::CUSTOMER_ACCOUNT_CREATE)->getAttribute($attributeCode);
    }

    /**
     * Returns format which will be applied for date field in javascript
     *
     * @return string
     */
    public function getDateFormat()
    {
        $dateFormat = $this->_localeDate->getDateFormat();
        /** Escape RTL characters which are present in some locales and corrupt formatting */
        $escapedDateFormat = preg_replace('/[^MmDdYy\/\.\-]/', '', $dateFormat);

        return $escapedDateFormat;
    }

    /**
     * @return CustomerForm
     */
    private function getAccountForm()
    {
        return $this->getFormByCode(self::CUSTOMER_ACCOUNT_CREATE);
    }

    /**
     * @return CustomerForm
     */
    private function getQuoteForm()
    {
        return $this->getFormByCode(DataPlugin::QUOTE_FORM);
    }

    /**
     * @param string $code
     * @return CustomerForm
     */
    private function getFormByCode(string $code)
    {
        if (!isset($this->forms[$code])) {
            $this->forms[$code] = $this->customerFormFactory->create()->setFormCode($code);
        }

        return $this->forms[$code];
    }
}

<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_RequestQuote
 */


namespace Amasty\RequestQuote\Test\Unit\Model\CheckoutConfigProvider;

use Amasty\Base\Model\MagentoVersion;
use Amasty\RequestQuote\Api\Data\QuoteInterface;
use Amasty\RequestQuote\Api\QuoteRepositoryInterface;
use Amasty\RequestQuote\Model\CheckoutConfigProvider\ShippingAddress;
use Amasty\RequestQuote\Model\Customer\Address\CustomerAddressDataFormatter as AmastyAddressDataFormatter;
use Amasty\RequestQuote\Model\Customer\Address\CustomerAddressDataFormatterFactory;
use Amasty\RequestQuote\Test\Unit\Traits\ObjectManagerTrait;
use Amasty\RequestQuote\Test\Unit\Traits\ReflectionTrait;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Address\CustomerAddressDataFormatter;
use Magento\Customer\Model\Address\CustomerAddressDataFormatter as MagentoAddressDataFormatter;
use Magento\Framework\DataObject;
use Magento\Quote\Model\Quote\Address;

/**
 * Class ShippingAddressTest
 *
 * @see ShippingAddress
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * phpcs:ignoreFile
 */
class ShippingAddressTest extends \PHPUnit\Framework\TestCase
{
    use ObjectManagerTrait;
    use ReflectionTrait;

    /**
     * @covers ShippingAddress::getConfig
     *
     * @dataProvider getConfigDataProvider
     *
     * @throws \ReflectionException
     */
    public function testGetConfig($isAmastyQuote, $shippingConfigured, $address, $expected)
    {
        $quoteRepository = $this->createMock(QuoteRepositoryInterface::class);
        $quoteRepository->expects($this->once())->method('isAmastyQuote')->willReturn($isAmastyQuote);
        $quoteRepository->expects($this->once())->method('get')->willReturn($this->getObjectManager()->getObject(
            DataObject::class,
            ['data' => [
                QuoteInterface::SHIPPING_CONFIGURE => $shippingConfigured
            ]]
        ));

        $customerAddressDataFormatterFactory = $this->createMock(CustomerAddressDataFormatterFactory::class);
        if (version_compare($this->getObjectManager()->getObject(MagentoVersion::class)->get(), '2.3.2', '<')) {
            $customerAddressDataFormatter = $this->createMock(AmastyAddressDataFormatter::class);
        } else {
            $customerAddressDataFormatter = $this->createMock(MagentoAddressDataFormatter::class);
        }
        $customerAddressDataFormatterFactory->expects($this->any())->method('create')->willReturn($customerAddressDataFormatter);
        $customerAddressDataFormatter->expects($this->any())->method('prepareAddress')->willReturn($address);

        $customerAddress = $this->createMock(\Magento\Customer\Api\Data\AddressInterface::class);

        $shippingAddress = $this->createMock(Address::class);
        $shippingAddress->expects($this->any())->method('exportCustomerAddress')->willReturn($customerAddress);

        $checkoutSession = $this->createMock(CheckoutSession::class);
        $checkoutSession->expects($this->any())->method('getQuote')->willReturn($this->getObjectManager()->getObject(
            DataObject::class,
            ['data' => [
                'shipping_address' => $shippingAddress
            ]]
        ));

        /** @var ShippingAddress $model */
        $model = $this->getObjectManager()->getObject(ShippingAddress::class, [
            'quoteRepository' => $quoteRepository,
            'customerAddressDataFormatterFactory' => $customerAddressDataFormatterFactory,
            'checkoutSession' => $checkoutSession
        ]);

        $actual = $model->getConfig();

        $this->assertEquals($expected, $actual);
    }

    /**
     * Data provider for getConfig test
     * @return array
     */
    public function getConfigDataProvider()
    {
        return [
            [
                true,
                false,
                [],
                []
            ],
            [
                true,
                true,
                [
                    'city' => 'vicecity'
                ],
                [
                    'amasty_quote' => [
                        'shipping_address' => [
                            'city' => 'vicecity'
                        ]
                    ]
                ]
            ],
            [
                true,
                false,
                [
                    'city' => 'vicecity'
                ],
                []
            ]
        ];
    }
}

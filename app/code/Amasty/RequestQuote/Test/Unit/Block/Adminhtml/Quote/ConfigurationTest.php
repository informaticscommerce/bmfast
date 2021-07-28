<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_RequestQuote
 */



namespace Amasty\RequestQuote\Test\Unit\Block\Adminhtml\Quote;

use Amasty\RequestQuote\Block\Adminhtml\Quote\Configuration;
use Amasty\RequestQuote\Model\Quote;
use Amasty\RequestQuote\Test\Unit\Traits\ObjectManagerTrait;
use Amasty\RequestQuote\Test\Unit\Traits\ReflectionTrait;

/**
 * Class ConfigurationTest
 *
 * @see Configuration
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * phpcs:ignoreFile
 */
class ConfigurationTest extends \PHPUnit\Framework\TestCase
{
    use ObjectManagerTrait;
    use ReflectionTrait;

    /**
     * @covers Configuration::getShippingData
     *
     * @dataProvider getShippingDataDataProvider
     *
     * @throws \ReflectionException
     */
    public function testGetShippingData($dataKey, $data1, $data2, $defaultValue, $expected)
    {
        $quoteSession = $this->createMock(\Amasty\RequestQuote\Model\Quote\Backend\Session::class);
        $quoteSession->expects($this->any())->method('getQuote')->willReturn($this->getObjectManager()->getObject(
            Quote::class,
            [
                'data' => $data1
            ]
        ));
        $quoteSession->expects($this->any())->method('getParentQuote')->willReturn($this->getObjectManager()->getObject(
            Quote::class,
            [
                'data' => $data2
            ]
        ));

        $model = $this->getObjectManager()->getObject(Configuration::class, [
            'quoteSession' => $quoteSession
        ]);

        $actual = $this->invokeMethod($model, 'getShippingData', [$dataKey, $defaultValue]);

        $this->assertEquals($expected, $actual);
    }

    /**
     * Data provider for getShippingData test
     * @return array
     */
    public function getShippingDataDataProvider()
    {
        return [
            [
                'test1',
                [
                    'test1' => true
                ],
                [
                    'test1' => false
                ],
                false,
                true
            ],
            [
                'test1',
                [],
                [],
                false,
                false
            ],
            [
                'test1',
                [
                    'test1' => false
                ],
                [
                    'test1' => true
                ],
                false,
                false
            ],
            [
                'test1',
                [],
                [
                    'test1' => false
                ],
                false,
                false
            ],
            [
                'test1',
                [],
                [
                    'test1' => true
                ],
                false,
                true
            ]
        ];
    }
}

<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Unit\Internal\Twig\Extension;

use OxidEsales\EshopCommunity\Core\Registry;
use OxidEsales\EshopCommunity\Internal\Twig\Extensions\OxPriceExtension;
use PHPUnit\Framework\TestCase;

class OxPriceExtensionTest extends TestCase
{

    /**
     * @var OxPriceExtension
     */
    private $oxidExtension;

    protected function setUp()
    {
        $config = Registry::getConfig();
        $this->oxidExtension = new OxPriceExtension($config);
        parent::setUp();
    }

    /**
     * @param $params
     * @param $expected
     *
     * @dataProvider getOxpriceProvider
     */
    public function testOxprice($params, $expected)
    {
        $price = $this->oxidExtension->oxprice($params);
        $this->assertEquals($expected, $price);
    }

    public function getOxpriceProvider()
    {
        return [
            [
                ['price' => 100],
                '100,00 €'
            ],
            [
                ['price' => null],
                ''
            ]
        ];
    }

    /**
     * @param $inputPrice
     * @param $expected
     *
     * @dataProvider getCalculateOxPriceProvider
     */
    public function testCalculateOxPrice($inputPrice, $expected)
    {
        $params['price'] = $inputPrice;
        $calculatedOxPrice = $this->oxidExtension->oxprice($params);
        $this->assertEquals($expected, $calculatedOxPrice);
    }

    /**
     * @return array
     */
    public function getCalculateOxPriceProvider()
    {
        $incorrectPriceObj = new \OxidEsales\Eshop\Core\Price();
        $incorrectPriceObj->setPrice(false);
        $correctPriceObj = new \OxidEsales\Eshop\Core\Price();
        $correctPriceObj->setPrice(120);

        return [
            [
                1, '1,00 €'
            ],
            [
                'incorrect', '0,00 €'
            ],
            [
                $incorrectPriceObj, '0,00 €'
            ],
            [
                $incorrectPriceObj, '0,00 €'
            ],
            [
                $correctPriceObj, '120,00 €'
            ]
        ];
    }

    /**
     * @param $currency
     * @param $price
     * @param $expected
     *
     * @dataProvider getFormattedOxPriceProvider
     */
    public function testGetFormattedOxPrice($currency, $price, $expected)
    {
        $params['currency'] = $currency;
        $params['price'] = $price;
        $formattedPrice = $this->oxidExtension->oxprice($params);
        $this->assertEquals($expected, $formattedPrice);
    }

    /**
     * @return array
     */
    public function getFormattedOxPriceProvider()
    {
        $price = 10000;

        return [
            [
                '', $price, '10.000,00'
            ],
            [
                '', -100, ''
            ],
            [
                $this->getCurrencyWithSepparator(['dec' => '-']), $price, '10.000-00'
            ],
            [
                $this->getCurrencyWithSepparator(['thousand' => '-']), $price, '10-000,00'
            ],
            [
                $this->getCurrencyWithSepparator(['sign' => '$']), $price, '10.000,00 $'
            ],
            [
                $this->getCurrencyWithSepparator(['decimal' => 4]), $price, '10.000,0000'
            ],
            [
                $this->getCurrencyWithSepparator(['sign' => '$', 'side' => 'Front']), $price, '$10.000,00'
            ],
            [
                $this->getCurrencyWithSepparator(['sign' => '$', 'side' => 'incorrect']), $price, '10.000,00 $'
            ]
        ];
    }

    /**
     * @param $currency_array
     *
     * @return \stdClass
     */
    private function getCurrencyWithSepparator($currency_array)
    {
        $currency = new \stdClass();
        foreach ($currency_array as $key => $value) {
            $currency->$key = $value;
        }

        return $currency;
    }

}

<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Internal\Twig\Extensions;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class OxPriceExtension
 *
 * @package OxidEsales\EshopCommunity\Internal\Twig\Extensions
 */
class OxPriceExtension extends AbstractExtension
{

    /**
     * @var \OxidEsales\Eshop\Core\Config
     */
    private $config;

    /**
     * OxPriceExtension constructor.
     *
     * @param \OxidEsales\Eshop\Core\Config $config
     */
    public function __construct(\OxidEsales\Eshop\Core\Config $config)
    {
        $this->config = $config;
    }

    /**
     * @return array|\Twig_Function[]
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('oxprice', [$this, 'oxprice'])
        ];
    }

    /**
     * @param array $params
     *
     * @return string
     */
    public function oxprice($params)
    {
        $output = '';
        $inputPrice = $params['price'];
        if (!is_null($inputPrice)) {
            $output = $this->calculateOxPrice($inputPrice, $params);
        }

        return $output;
    }

    /**
     * @param mixed $inputPrice
     * @param array $params
     *
     * @return string
     */
    private function calculateOxPrice($inputPrice, $params)
    {
        $price = ($inputPrice instanceof \OxidEsales\Eshop\Core\Price) ? $inputPrice->getPrice() : floatval($inputPrice);
        $currency = isset($params['currency']) ? $params['currency'] : $this->config->getActShopCurrencyObject();
        $output = '';

        if (is_numeric($price)) {
            $output = $this->getFormattedOxPrice($currency, $price);
        }

        return $output;
    }

    /**
     * @param object $currency active currency object
     * @param mixed  $price
     *
     * @return string
     */
    private function getFormattedOxPrice($currency, $price)
    {
        $output = '';
        $decimalSeparator = isset($currency->dec) ? $currency->dec : ',';
        $thousandsSeparator = isset($currency->thousand) ? $currency->thousand : '.';
        $currencySymbol = isset($currency->sign) ? $currency->sign : '';
        $currencySymbolLocation = isset($currency->side) ? $currency->side : '';
        $decimals = isset($currency->decimal) ? (int) $currency->decimal : 2;

        if ((float) $price > 0 || $currencySymbol) {
            $price = number_format($price, $decimals, $decimalSeparator, $thousandsSeparator);
            $output = (isset($currencySymbolLocation) && $currencySymbolLocation == 'Front') ? $currencySymbol . $price : $price . ' ' . $currencySymbol;
        }

        $output = trim($output);

        return $output;
    }
}

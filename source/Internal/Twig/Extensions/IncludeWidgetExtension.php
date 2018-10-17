<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Internal\Twig\Extensions;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class IncludeWidgetExtension
 *
 * @package OxidEsales\EshopCommunity\Internal\Twig\Extensions
 */
class IncludeWidgetExtension extends AbstractExtension
{

    /**
     * @var \OxidEsales\Eshop\Core\WidgetControl
     */
    private $widgetControl;

    /**
     * IncludeWidgetExtension constructor.
     *
     * @param \OxidEsales\Eshop\Core\WidgetControl $widgetControl
     */
    public function __construct(\OxidEsales\Eshop\Core\WidgetControl $widgetControl)
    {
        $this->widgetControl = $widgetControl;
    }

    /**
     * @return array|\Twig_Function[]
     */
    public function getFunctions()
    {
        return [new TwigFunction('includeWidget', [$this, 'includeWidget'])];
    }

    /**
     * @param array $params
     *
     * @return mixed
     */
    public function includeWidget($params)
    {
        $class = $this->getIncludeWidgetClass($params);
        $parentViews = $this->getIncludeWidgetParentViews($params);
        $formattedParams = $this->getIncludeWidgetFormattedParams($params);

        return $this->widgetControl->start($class, null, $formattedParams, $parentViews);
    }

    /**
     * @param array $params
     *
     * @return string
     */
    private function getIncludeWidgetClass($params)
    {
        return isset($params['cl']) ? strtolower($params['cl']) : '';
    }

    /**
     * @param array $params
     *
     * @return array|null
     */
    private function getIncludeWidgetParentViews($params)
    {
        $parentViews = null;
        if (!empty($params["_parent"])) {
            $parentViews = explode("|", $params["_parent"]);
        }

        return $parentViews;
    }

    /**
     * @param array $params
     *
     * @return mixed
     */
    private function getIncludeWidgetFormattedParams($params)
    {
        unset($params['cl']);
        if (!empty($params["_parent"])) {
            unset($params["_parent"]);
        }

        return $params;
    }
}

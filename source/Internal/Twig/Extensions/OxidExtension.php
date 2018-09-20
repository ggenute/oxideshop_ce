<?php

namespace OxidEsales\EshopCommunity\Internal\Twig\Extensions;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class OxidExtension
 */
class OxidExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [new TwigFunction('oxid_include_widget', [$this, 'oxidIncludeWidget'])];
    }

    public function oxidIncludeWidget($params)
    {
        $class = $this->getIncludeWidgetClass($params);
        $parentViews = $this->getIncludeWidgetParentViews($params);
        $formattedParams = $this->getIncludeWidgetFormattedParams($params);

        $widgetControl = \OxidEsales\Eshop\Core\Registry::get(\OxidEsales\Eshop\Core\WidgetControl::class);
        return $widgetControl->start($class, null, $formattedParams, $parentViews);
    }

    private function getIncludeWidgetClass($params)
    {
        return isset($params['cl']) ? strtolower($params['cl']) : '';
    }

    private function getIncludeWidgetParentViews($params)
    {
        $parentViews = null;
        if(!empty($params["_parent"])) {
            $parentViews = explode("|", $params["_parent"]);
        }
        return $parentViews;
    }

    private function getIncludeWidgetFormattedParams($params)
    {
        unset($params['cl']);
        if(!empty($params["_parent"])) {
            unset($params["_parent"]);
        }
        return $params;
    }

}
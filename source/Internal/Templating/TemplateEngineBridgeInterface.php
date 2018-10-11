<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Internal\Templating;

use Symfony\Component\Templating\EngineInterface;

/**
 * Interface TemplateEngineBridgeInterface
 */
interface TemplateEngineBridgeInterface extends EngineInterface
{
    /**
     * @return BaseEngineInterface
     */
    public function getEngineInstance();

    /**
     * @param string $templateName The template name
     * @param array  $viewData     An array of parameters to pass to the template
     * @param string $cacheId      The id for template caching
     *
     * @return string
     */
    public function renderTemplate($templateName, $viewData, $cacheId = null);
}

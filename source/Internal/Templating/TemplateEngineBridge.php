<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Internal\Templating;

use Symfony\Component\Templating\DelegatingEngine;

/**
 * Class TemplateEngineBridge
 */
class TemplateEngineBridge extends DelegatingEngine implements TemplateEngineBridgeInterface
{
    private $fallbackEngine;

    /**
     * @return BaseEngineInterface
     */
    public function getEngineInstance()
    {
        $this->defineDefaultEngine();
        return $this->fallbackEngine;
    }

    /**
     * @param string $templateName The template name
     * @param array  $viewData     An array of parameters to pass to the template
     * @param string $cacheId      The id for template caching
     *
     * @return string
     */
    public function renderTemplate($templateName, $viewData, $cacheId = null)
    {
        $templating = $this->getEngine($templateName);
        $templating->setCacheId($cacheId);

        return $templating->render($templateName, $viewData);
    }

    public function getEngine($name)
    {
        $this->defineDefaultEngine();
        try {
            $engine = parent::getEngine($name);
        } catch (\RuntimeException $e) {
            $engine = $this->fallbackEngine;
        }
        return $engine;
    }

    private function defineDefaultEngine()
    {
        if (isset($this->engines[0])) {
            $this->fallbackEngine = $this->engines[0];
        } else {
            throw new \RuntimeException('No engine was registered');
        }
    }
}

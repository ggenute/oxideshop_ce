<?php
declare(strict_types=1);

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Internal\Configuration\Module\DataObject;

/**
 * @internal
 */
class ShopConfiguration
{
    /**
     * @param string $id
     *
     * @return ModuleConfiguration
     */
    public function getModuleConfig(string $id) : ModuleConfiguration
    {
        return new ModuleConfiguration();
    }
}

<?php declare(strict_types=1);
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Internal\Console;

use OxidEsales\Facts\Facts;
use Webmozart\PathUtil\Path;

/**
 * Bootstraps OXID eShop by specified shop id.
 */
class ShopBootstrapper
{
    /**
     * @param int $shopId
     */
    public function bootstrap(int $shopId)
    {
        if ($shopId !== 0) {
            $_POST['shp'] = $shopId;
        }
        $bootstrapFilePath = Path::join((new Facts())->getSourcePath(), 'bootstrap.php');
        require_once $bootstrapFilePath;
    }
}

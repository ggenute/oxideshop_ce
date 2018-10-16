<?php declare(strict_types=1);
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Internal\Console;

use OxidEsales\EshopCommunity\Internal\Application\ShopAwareServiceInterface;
use OxidEsales\EshopCommunity\Internal\Application\ShopAwareTrait;
use Symfony\Component\Console\Command\Command;

/**
 * Class is being used to detect if command active for active shop.
 */
abstract class AbstractShopAwareCommand extends Command implements ShopAwareServiceInterface
{
    use ShopAwareTrait;
}

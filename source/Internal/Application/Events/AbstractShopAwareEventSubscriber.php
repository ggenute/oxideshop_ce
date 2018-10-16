<?php declare(strict_types=1);
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Internal\Application\Events;

use OxidEsales\EshopCommunity\Internal\Application\ShopAwareServiceInterface;
use OxidEsales\EshopCommunity\Internal\Application\ShopAwareTrait;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class ShopAwareEventSubscriber
 */
abstract class AbstractShopAwareEventSubscriber implements EventSubscriberInterface, ShopAwareServiceInterface
{
    use ShopAwareTrait;
}

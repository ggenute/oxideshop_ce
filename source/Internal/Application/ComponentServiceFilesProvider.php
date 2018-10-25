<?php declare(strict_types=1);
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Internal\Application;

use Composer\Repository\WritableRepositoryInterface;

/**
 * Provides list of components service files.
 * @internal
 */
class ComponentServiceFilesProvider
{
    /**
     * @var WritableRepositoryInterface
     */
    private $localRepository;

    /**
     * @param WritableRepositoryInterface $localRepository
     */
    public function __construct(WritableRepositoryInterface $localRepository)
    {
        $this->localRepository = $localRepository;
    }

    /**
     * @return array
     */
    public function getServiceFiles(): array
    {
        $commandsClasses = [];
        $packages = $this->localRepository->getPackages();
        foreach ($packages as $package) {
            if (isset($package->getExtra()['oxideshop-component'])) {
                $path = $package->getTargetDir();
            }
        }
        return $commandsClasses;
    }
}

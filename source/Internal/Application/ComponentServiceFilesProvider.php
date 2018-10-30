<?php declare(strict_types=1);
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Internal\Application;

use Composer\Repository\WritableRepositoryInterface;
use OxidEsales\Facts\Facts;
use Webmozart\PathUtil\Path;

/**
 * Provides list of components service files.
 * @internal
 */
class ComponentServiceFilesProvider
{
    const SERVICE_FILE_NAME = 'services.yaml';

    /**
     * @var WritableRepositoryInterface
     */
    private $localRepository;

    /**
     * @var Facts
     */
    private $facts;

    /**
     * @param WritableRepositoryInterface $localRepository
     */
    public function __construct(WritableRepositoryInterface $localRepository, Facts $facts)
    {
        $this->localRepository = $localRepository;
        $this->facts = $facts;
    }

    /**
     * Returns components service files paths.
     *
     * @return array
     */
    public function getServiceFiles(): array
    {
        $paths = [];
        $packages = $this->localRepository->getPackages();
        foreach ($packages as $package) {
            if ($package->getType() === 'oxideshop-component') {
                $paths[] = Path::join($this->facts->getVendorPath(), $package->getName(), static::SERVICE_FILE_NAME);
            }
        }
        return $paths;
    }
}

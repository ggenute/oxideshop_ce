<?php
declare(strict_types=1);

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Internal\Application;

use OxidEsales\EshopCommunity\Internal\Console\ConsoleCommandPass;
use OxidEsales\Facts\Facts;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder as SymfonyContainerBuilder;
use Symfony\Component\EventDispatcher\DependencyInjection\RegisterListenersPass;

/**
 * @internal
 */
class ContainerBuilder
{
    /**
     * @var array
     */
    private $serviceFilePaths = [
        'services.yaml',
    ];

    /**
     * @var Facts
     */
    private $facts;

    /**
     * @param Facts $facts
     */
    public function __construct(Facts $facts)
    {
        $this->facts = $facts;
    }

    /**
     * @return Container
     */
    public function getContainer(): Container
    {
        $symfonyContainer = new SymfonyContainerBuilder();
        $symfonyContainer->addCompilerPass(new RegisterListenersPass());
        $symfonyContainer->addCompilerPass(new ConsoleCommandPass());
        $this->loadServiceFiles($symfonyContainer);
        if ($this->facts->isProfessional()) {
            $this->loadEditionServices($symfonyContainer, $this->facts->getProfessionalEditionRootPath());
        }
        if ($this->facts->isEnterprise()) {
            $this->loadEditionServices($symfonyContainer, $this->facts->getProfessionalEditionRootPath());
            $this->loadEditionServices($symfonyContainer, $this->facts->getEnterpriseEditionRootPath());
        }
        $this->loadProjectServices($symfonyContainer);

        return $symfonyContainer;
    }

    /**
     * @param SymfonyContainerBuilder $symfonyContainer
     */
    private function loadServiceFiles(SymfonyContainerBuilder $symfonyContainer)
    {
        foreach ($this->serviceFilePaths as $path) {
            $loader = new YamlFileLoader($symfonyContainer, new FileLocator(__DIR__));
            $loader->load($path);
        }
    }

    /**
     * Loads a 'project.yaml' file if it can be found in the shop directory
     *
     * @param SymfonyContainerBuilder $symfonyContainer
     *
     */
    private function loadProjectServices(SymfonyContainerBuilder $symfonyContainer)
    {

        try {
            $loader = new YamlFileLoader($symfonyContainer, new FileLocator($this->facts->getSourcePath()));
            $loader->load('project.yaml');
        } catch (\Exception $e) {
            // pass
        }
    }

    /**
     * @param SymfonyContainerBuilder $symfonyContainer
     * @param string                  $editionPath
     */
    private function loadEditionServices(SymfonyContainerBuilder $symfonyContainer, string $editionPath)
    {
        $servicesLoader = new YamlFileLoader($symfonyContainer, new FileLocator($editionPath));
        $servicesLoader->load('Internal/Application/services.yaml');
    }
}

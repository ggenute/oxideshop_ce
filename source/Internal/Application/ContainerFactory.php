<?php
declare(strict_types=1);

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Internal\Application;

use Composer\IO\NullIO;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Facts\Facts;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Webmozart\PathUtil\Path;

/**
  *
 * @internal
 */
class ContainerFactory
{
    /**
     * @var self
     */
    private static $instance = null;

    /**
     * @var ContainerInterface
     */
    private $symfonyContainer = null;

    /**
     * ContainerFactory constructor.
     *
     * Make the constructor private
     */
    private function __construct()
    {
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        if ($this->symfonyContainer === null) {
            $this->initializeContainer();
        }

        return $this->symfonyContainer;
    }

    /**
     * Loads container from cache if available, otherwise
     * create the container from scratch.
     */
    private function initializeContainer()
    {
        $cacheFilePath = $this->getCacheFilePath();

        if (file_exists($cacheFilePath)) {
            $this->loadContainerFromCache($cacheFilePath);
        } else {
            $this->getCompiledSymfonyContainer();
            $this->saveContainerToCache($cacheFilePath);
        }
    }

    /**
     * @param string $cachefile
     */
    private function loadContainerFromCache($cachefile)
    {
        include_once $cachefile;
        $this->symfonyContainer = new \ProjectServiceContainer();
    }

    /**
     * Returns compiled Container
     */
    private function getCompiledSymfonyContainer()
    {
        if (getenv('COMPOSER_HOME') === false) {
            putenv('COMPOSER_HOME=~/');
        }
        $composer = \Composer\Factory::create(new NullIO(), Path::join((new Facts())->getShopRootPath(), 'composer.json'));
        $repositoryManager = $composer->getRepositoryManager();
        $containerBuilder = new ContainerBuilder(
            new Facts(),
            new ComponentServiceFilesProvider($repositoryManager->getLocalRepository(), new Facts())
        );
        $this->symfonyContainer = $containerBuilder->getContainer();
        $this->symfonyContainer->compile();
    }

    /**
     * Dumps the compiled container to the cachefile.
     *
     * @param string $cachefile
     */
    private function saveContainerToCache($cachefile)
    {
        $dumper = new PhpDumper($this->symfonyContainer);
        file_put_contents($cachefile, $dumper->dump());
    }

    /**
     * @todo: move it to another place.
     *
     * @return string
     */
    private function getCacheFilePath()
    {
        $compileDir = Registry::getConfig()->getConfigParam('sCompileDir');

        return $compileDir . '/containercache.php';
    }

    /**
     * @return ContainerFactory
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new ContainerFactory();
        }
        return self::$instance;
    }
}

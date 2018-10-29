<?php declare(strict_types=1);
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Unit\Internal\Application;

use Composer\Package\PackageInterface;
use Composer\Repository\WritableRepositoryInterface;
use OxidEsales\EshopCommunity\Internal\Application\ComponentServiceFilesProvider;
use PHPUnit\Framework\TestCase;


class ComponentServiceFilesProviderTest extends TestCase
{
    public function testGetPathsWhenProvided()
    {
        $localRepositoryStub = $this->getLocalRepositoryStub('oxideshop-component');

        $componentServiceFilesProvider = new ComponentServiceFilesProvider($localRepositoryStub);

        $this->assertSame(['path_to_component/' . ComponentServiceFilesProvider::SERVICE_FILE_NAME], $componentServiceFilesProvider->getServiceFiles());
    }

    public function testWhenNotOxidEshopComponent()
    {
        $localRepositoryStub = $this->getLocalRepositoryStub('oxideshop');

        $componentServiceFilesProvider = new ComponentServiceFilesProvider($localRepositoryStub);

        $this->assertSame([], $componentServiceFilesProvider->getServiceFiles());
    }

    /**
     * @param string $componentType
     * @return WritableRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private function getLocalRepositoryStub(string $componentType)
    {
        $packageStub = $this->getMockBuilder(PackageInterface::class)->getMock();
        $packageStub->method('getType')->willReturn($componentType);
        $packageStub->method('getTargetDir')->willReturn('path_to_component');
        $localRepositoryStub = $this->getMockBuilder(WritableRepositoryInterface::class)->getMock();
        $packages = [
            $packageStub
        ];
        $localRepositoryStub->method('getPackages')->willReturn($packages);

        return $localRepositoryStub;
    }
}

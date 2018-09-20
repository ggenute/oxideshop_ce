<?php

namespace OxidEsales\EshopCommunity\Tests\Unit\Internal\Twig\Extension;

use OxidEsales\EshopCommunity\Internal\Twig\Extensions\OxidExtension;

class OxidIncludeWidget extends \OxidTestCase
{

    /**
     * @var OxidExtension
     */
    protected $extension;

    protected function setUp()
    {
        parent::setUp();
        $this->extension = new OxidExtension();
    }

    public function testGetIncludeWidgetClassEmpty()
    {
        $arguments = [['foo' => 'bar']];
        $class = $this->callMethod($this->extension, 'getIncludeWidgetClass', $arguments);
        $this->assertEquals('', $class);
    }

    public function testGetIncludeWidgetClass()
    {
        $arguments = [['cl' => 'foo']];
        $class = $this->callMethod($this->extension, 'getIncludeWidgetClass', $arguments);
        $this->assertEquals('foo', $class);
    }

    public function testGetIncludeWidgetClassStringToLower()
    {
        $arguments = [['cl' => 'FOO']];
        $class = $this->callMethod($this->extension, 'getIncludeWidgetClass', $arguments);
        $this->assertEquals('foo', $class);
    }

    public function testGetIncludeWidgetParentViewsEmpty()
    {
        $arguments = [['cl' => 'FOO']];
        $parentViews = $this->callMethod($this->extension, 'getIncludeWidgetParentViews', $arguments);
        $this->assertEquals('', $parentViews);
    }

    public function testGetIncludeWidgetParentViews()
    {
        $arguments = [['_parent' => 'foo']];
        $parentViews = $this->callMethod($this->extension, 'getIncludeWidgetParentViews', $arguments);
        $this->assertEquals(['foo'], $parentViews);
    }

    public function testGetIncludeWidgetParentViewsMultipleParents()
    {
        $arguments = [['_parent' => 'foo|bar']];
        $parentViews = $this->callMethod($this->extension, 'getIncludeWidgetParentViews', $arguments);
        $this->assertEquals(['foo', 'bar'], $parentViews);
    }

    public function testGetIncludeWidgetFormattedParamsNothingToUnset()
    {
        $arguments = [['foo' => 'bar']];
        $formattedParams = $this->callMethod($this->extension, 'getIncludeWidgetFormattedParams', $arguments);
        $this->assertEquals(['foo' => 'bar'], $formattedParams);
    }

    public function testGetIncludeWidgetFormattedParams()
    {
        $arguments = [['foo' => 'bar', 'cl' => 'class', '_parent' => 'parent']];
        $formattedParams = $this->callMethod($this->extension, 'getIncludeWidgetFormattedParams', $arguments);
        $this->assertEquals(['foo' => 'bar'], $formattedParams);
    }

    public function testGetIncludeWidgetFormattedParamsUnsetClass()
    {
        $arguments = [['foo' => 'bar', 'cl' => 'class']];
        $formattedParams = $this->callMethod($this->extension, 'getIncludeWidgetFormattedParams', $arguments);
        $this->assertEquals(['foo' => 'bar'], $formattedParams);
    }

    public function testGetIncludeWidgetFormattedParamsUnsetParent()
    {
        $arguments = [['foo' => 'bar', '_parent' => 'parent']];
        $formattedParams = $this->callMethod($this->extension, 'getIncludeWidgetFormattedParams', $arguments);
        $this->assertEquals(['foo' => 'bar'], $formattedParams);
    }

    public static function callMethod($obj, $name, array $args)
    {
        $class = new \ReflectionClass($obj);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method->invokeArgs($obj, $args);
    }

}

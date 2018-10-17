<?php

namespace OxidEsales\EshopCommunity\Tests\Unit\Internal\Twig\Extension;

use OxidEsales\Eshop\Core\WidgetControl;
use OxidEsales\EshopCommunity\Internal\Twig\Extensions\IncludeWidgetExtension;
use PHPUnit\Framework\TestCase;

class IncludeWidgetExtensionTest extends TestCase
{

    /**
     * @var IncludeWidgetExtension
     */
    protected $extension;

    protected function setUp()
    {
        parent::setUp();
        /**
         * @var WidgetControl $widgetControl
         */
        $widgetControl = $this->getMockBuilder('OxidEsales\Eshop\Core\WidgetControl')->getMock();
        $widgetControl->method('start')->will($this->returnCallback([$this, 'mockStart']));
        $this->extension = new IncludeWidgetExtension($widgetControl);
    }

    public function mockStart()
    {
        $args = func_get_args();

        return $args;
    }

    public function testGetIncludeWidgetClassEmpty()
    {
        $arguments = ['foo' => 'bar'];
        $formattedArguments = $this->extension->IncludeWidget($arguments);
        $expectedArguments = ['', null, ['foo' => 'bar'], null];
        $this->assertEquals($expectedArguments, $formattedArguments);
    }

    public function testGetIncludeWidgetClass()
    {
        $arguments = ['cl' => 'foo'];
        $formattedArguments = $this->extension->IncludeWidget($arguments);
        $expectedArguments = ['foo', null, [], null];
        $this->assertEquals($expectedArguments, $formattedArguments);
    }

    public function testGetIncludeWidgetClassStringToLower()
    {
        $arguments = ['cl' => 'FOO'];
        $formattedArguments = $this->extension->IncludeWidget($arguments);
        $expectedArguments = ['foo', null, [], null];
        $this->assertEquals($expectedArguments, $formattedArguments);
    }

    public function testGetIncludeWidgetParentViewsEmpty()
    {
        $arguments = ['cl' => 'FOO'];
        $formattedArguments = $this->extension->IncludeWidget($arguments);
        $expectedArguments = ['foo', null, [], null];
        $this->assertEquals($expectedArguments, $formattedArguments);
    }

    public function testGetIncludeWidgetParentViews()
    {
        $arguments = ['_parent' => 'foo'];
        $formattedArguments = $this->extension->IncludeWidget($arguments);
        $expectedArguments = ['', null, [], ['foo']];
        $this->assertEquals($expectedArguments, $formattedArguments);
    }

    public function testGetIncludeWidgetParentViewsMultipleParents()
    {
        $arguments = ['_parent' => 'foo|bar'];
        $formattedArguments = $this->extension->IncludeWidget($arguments);
        $expectedArguments = ['', null, [], ['foo', 'bar']];
        $this->assertEquals($expectedArguments, $formattedArguments);
    }

    public function testGetIncludeWidgetFormattedParamsNothingToUnset()
    {
        $arguments = ['foo' => 'bar'];
        $formattedArguments = $this->extension->IncludeWidget($arguments);
        $expectedArguments = ['', null, ['foo' => 'bar'], null];
        $this->assertEquals($expectedArguments, $formattedArguments);
    }

    public function testGetIncludeWidgetFormattedParams()
    {
        $arguments = ['foo' => 'bar', 'cl' => 'class', '_parent' => 'parent'];
        $formattedArguments = $this->extension->IncludeWidget($arguments);
        $expectedArguments = ['class', null, ['foo' => 'bar'], ['parent']];
        $this->assertEquals($expectedArguments, $formattedArguments);
    }

    public function testGetIncludeWidgetFormattedParamsUnsetClass()
    {
        $arguments = ['foo' => 'bar', 'cl' => 'class'];
        $formattedArguments = $this->extension->IncludeWidget($arguments);
        $expectedArguments = ['class', null, ['foo' => 'bar'], null];
        $this->assertEquals($expectedArguments, $formattedArguments);
    }

    public function testGetIncludeWidgetFormattedParamsUnsetParent()
    {
        $arguments = ['foo' => 'bar', '_parent' => 'parent'];
        $formattedArguments = $this->extension->IncludeWidget($arguments);
        $expectedArguments = ['', null, ['foo' => 'bar'], ['parent']];
        $this->assertEquals($expectedArguments, $formattedArguments);
    }

}

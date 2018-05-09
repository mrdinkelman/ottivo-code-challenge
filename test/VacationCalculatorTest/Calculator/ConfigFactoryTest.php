<?php

namespace VacationCalculatorTest\Calculator;

use VacationCalculator\Calculator\Config;
use VacationCalculator\Calculator\ConfigFactory;
use PHPUnit\Framework\TestCase;
use Interop\Container\ContainerInterface;

class ConfigFactoryTest extends TestCase
{
    public function testInvokeThrownExceptionWhenConfigIsNotSet()
    {
        $container = $this->prepareContainer([]);

        $factory = new ConfigFactory();

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Employee config is not set');

        $factory($container->reveal());
    }

    public function testInvokeReturnsConfigInstance()
    {
        $container = $this->prepareContainer(['employee' => [ 'something' ]]);

        $factory = new ConfigFactory();
        $result = $factory($container->reveal());

        $this->assertInstanceOf(Config::class, $result);
    }

    protected function prepareContainer($options)
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->willReturn($options);

        return $container;
    }
}

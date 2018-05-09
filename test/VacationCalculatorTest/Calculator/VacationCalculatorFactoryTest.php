<?php

namespace VacationCalculatorTest\Calculator;

use VacationCalculator\Calculator\Config;
use VacationCalculator\Calculator\VacationCalculator;
use VacationCalculator\Calculator\VacationCalculatorFactory;
use PHPUnit\Framework\TestCase;
use Interop\Container\ContainerInterface;

class VacationCalculatorFactoryTest extends TestCase
{
    public function testInvokeReturnsCalculatorInstance()
    {
        $config = $this->prophesize(Config::class)->reveal();

        $container = $this->prophesize(ContainerInterface::class);
        $container->get(Config::class)->willReturn($config);

        $factory = new VacationCalculatorFactory();
        $result = $factory($container->reveal());

        $this->assertInstanceOf(VacationCalculator::class, $result);
    }
}

<?php

namespace VacationCalculatorTest\Logger;

use VacationCalculator\Logger\LoggerFactory;
use PHPUnit\Framework\TestCase;
use Interop\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class LoggerFactoryTest extends TestCase
{
    public function testInvokeReturnsLoggerInstance()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->willReturn([
            'logger' => [
                'vacation-calculator' => [
                    'file' => 'somewhere-to-write.log',
                    'level' => \Monolog\Logger::DEBUG,
                ],
            ]
        ]);

        $factory = new LoggerFactory();
        $result = $factory($container->reveal());

        $this->assertInstanceOf(LoggerInterface::class, $result);
    }
}


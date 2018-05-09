<?php

namespace VacationCalculatorTest\Command;

use VacationCalculator\Calculator\CalculatorException;
use VacationCalculator\Calculator\Config;
use VacationCalculator\Command\VacationCalculatorCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use VacationCalculator\Calculator\VacationCalculator;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Tester\CommandTester;

class VacationCalculatorCommandTest extends TestCase
{
    public function testExecuteReturnsInvalidArgumentCodeWhenYearIsNotValid()
    {
        $application = new Application('Employee CLI');

        $vacationCalculator = $this->prophesize(VacationCalculator::class)->reveal();
        $entityManager = $this->prophesize(EntityManagerInterface::class)->reveal();
        $logger = $this->prophesize(LoggerInterface::class)->reveal();

        $command = new VacationCalculatorCommand($vacationCalculator, $entityManager, $logger);
        $application->add($command);

        $command = $application->find('vacation-calculator:employee:calculate');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'year' => 123,
        ]);

        $this->assertEquals(1, $commandTester->getStatusCode());
        $this->assertContains(
            'Invalid input arguments received. Please check arguments and try again',
            $commandTester->getDisplay()
        );
    }
}

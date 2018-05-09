<?php

namespace VacationCalculatorTest\Calculator;

use VacationCalculator\Calculator\CalculatorException;
use PHPUnit\Framework\TestCase;

class CalculatorExceptionTest extends TestCase
{
    public function testEmployeeIsNotSet()
    {
        $this->expectException(CalculatorException::class);
        $this->expectExceptionMessage('Employee is not set via setter. Please set employee instance first');

        throw CalculatorException::employeeIsNotSet();
    }

    public function testUnableToGetWorkInterval()
    {
        $datetime = new \DateTime('1990-01-02 03:04:05');

        $this->expectException(CalculatorException::class);

        $this->expectExceptionMessage('Unable to get work interval for received date - 1990-01-02');

        throw CalculatorException::unableToGetWorkInterval($datetime);
    }
}

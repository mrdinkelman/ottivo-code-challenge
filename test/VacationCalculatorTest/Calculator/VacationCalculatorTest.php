<?php

namespace AppTest\Calculator;

use VacationCalculator\Calculator\CalculatorException;
use VacationCalculator\Calculator\Config;
use VacationCalculator\Calculator\VacationCalculator;
use VacationCalculator\Entity\Employee;
use PHPUnit\Framework\TestCase;

class VacationCalculatorTest extends TestCase
{
    private $config;

    protected function setUp()
    {
        $config = $this->getMockBuilder(Config::class)->getMock();
        $config->method('getDefaultVacationDaysCount')->willReturn(26);
        $config->method('getExtraVacationMinimumAge')->willReturn(30);
        $config->method('getExtraVacationDaysInEveryXYear')->willReturn(5);

        $this->config = $config;
    }

    public function testSetEmployee()
    {
        $employee = $this->getEmployee();

        $calculator = $this->getCalculatorInstance();
        $result = $calculator->setEmployee($employee);

        $this->assertInstanceOf(VacationCalculator::class, $result);
    }

    public function testGetEmployeeThrowsExceptionWhenEmployeeIsNotSet()
    {
        $calculator = $this->getCalculatorInstance();

        $this->expectException(CalculatorException::class);
        $calculator->getEmployee();
    }

    public function testGetEmployee()
    {
        $employee = $this->getEmployee();

        $calculator = $this->getCalculatorInstance();
        $calculator->setEmployee($employee);

        $this->assertInstanceOf(Employee::class, $calculator->getEmployee());
        $this->assertSame($employee, $calculator->getEmployee());
    }

    public function testSetDateTime()
    {
        $calculator = $this->getCalculatorInstance();
        $result = $calculator->setDateTime();

        $this->assertInstanceOf(VacationCalculator::class, $result);

        $dateTime = new \DateTime();
        $result = $calculator->setDateTime($dateTime);

        $this->assertInstanceOf(VacationCalculator::class, $result);
    }

    public function testGetDateTimeReturnNewDateTimeObjectWhenSetterIsNotCalled()
    {
        $calculator = $this->getCalculatorInstance();
        $result = $calculator->getDateTime();

        $this->assertInstanceOf(\DateTime::class, $result);
    }

    public function testGetDateTime()
    {
        $dateTime = new \DateTime();

        $calculator = $this->getCalculatorInstance();
        $calculator->setDateTime($dateTime);

        $result = $calculator->getDateTime();

        $this->assertInstanceOf(\DateTime::class, $result);
        $this->assertSame($dateTime, $result);
    }

    public function testCalculateThrowsExceptionWhenWorkIntervalIsNotDetected()
    {
        $dateTime = new \DateTime();

        $calculator = $this->getCalculatorInstance();
        $calculator->setDateTime($dateTime);
        $calculator->setEmployee($this->getEmployee());

        $this->expectException(CalculatorException::class);
        $calculator->calculate();
    }

    /**
     * @param $resultProvider
     * @dataProvider validResultProvider
     */
    public function testCalculate($resultProvider)
    {
        $dateTime = new \DateTime($resultProvider[0] .'-12-31 23:59:59');

        $calculator = $this->getCalculatorInstance();
        $calculator->setDateTime($dateTime);
        $calculator->setEmployee($resultProvider[1]);

        $this->assertEquals($resultProvider[2], $calculator->calculate());
    }

    protected function getCalculatorInstance()
    {
        return new VacationCalculator($this->config);
    }

    public function validResultProvider()
    {
        return [
            // year to calculate, employee instance, special days, result

            [[ 2001, $this->getEmployee('1950-12-30', '2001-01-01'), 30 ]],
            [[ 2001, $this->getEmployee('1950-12-30', '2001-01-15'), 27 ]],
            [[ 2001, $this->getEmployee('1950-12-30', '2001-01-01', 60), 60 ]],
            [[ 2001, $this->getEmployee('1950-12-30', '2001-01-15', 60), 55 ]],

            [[ 2001, $this->getEmployee('1966-09-06', '2001-01-01'), 27 ]],
            [[ 2001, $this->getEmployee('1966-09-06', '2001-01-15'), 24 ]],
            [[ 2001, $this->getEmployee('1966-09-06', '2001-01-01', 60), 60 ]],
            [[ 2001, $this->getEmployee('1966-09-06', '2001-01-15', 60), 55 ]],

            [[ 2001, $this->getEmployee('1981-01-01', '2001-01-01'), 26 ]],
            [[ 2001, $this->getEmployee('1981-01-01', '2001-01-15'), 23 ]],
            [[ 2001, $this->getEmployee('1981-01-01', '2001-01-01', 60), 60 ]],
            [[ 2001, $this->getEmployee('1981-01-01', '2001-01-15', 60), 55 ]],
        ];
    }

    protected function getEmployee($dateOfBirth = null, $dateStart = null, $specialDays = null)
    {
        $employee = new Employee();

        if ($dateOfBirth !== null) {
            $dateOfBirth = new \DateTime($dateOfBirth);
            $employee->setDateOfBirth($dateOfBirth);
        }

        if ($dateStart !== null) {
            $dateStart = new \DateTime($dateStart);
            $employee->setDateStart($dateStart);
        }

        if ($specialDays > 0) {
            $employee->setSpecialDays($specialDays);
        }

        return $employee;
    }
}

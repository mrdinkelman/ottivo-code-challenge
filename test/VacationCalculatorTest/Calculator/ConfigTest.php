<?php

namespace VacationCalculatorTest\Calculator;

use VacationCalculator\Calculator\Config;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    public function testGetDefaultVacationDaysCountThrowsExceptionWhenValueIsNotSet()
    {
        $options = $this->getValidOptions();
        unset($options['minimum-vacation-days']);

        $config = new Config($options);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Minimum vacation days is not set via config');

        $config->getDefaultVacationDaysCount();

        $options['minimum-vacation-days'] = -1;

        $config = new Config($options);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Minimum vacation day must be a positive integer');

        $config->getDefaultVacationDaysCount();
    }

    public function testGetDefaultVacationDaysCount()
    {
        $options = $this->getValidOptions();
        $config = new Config($options);

        $this->assertEquals(12, $config->getDefaultVacationDaysCount());
    }

    public function testGetExtraVacationMinimumAgeThrowsExceptionWhenValueIsNotSet()
    {
        $options = $this->getValidOptions();
        unset($options['extra-day-minimum-age']);

        $config = new Config($options);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Minimum age for extra vacation days is not set via config');

        $config->getExtraVacationMinimumAge();

        $options['extra-day-minimum-age'] = -1;

        $config = new Config($options);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Minimum age for extra vacation days must be a positive integer');

        $config->getExtraVacationMinimumAge();
    }
    
    public function testGetExtraVacationMinimumAge()
    {
        $options = $this->getValidOptions();
        $config = new Config($options);

        $this->assertEquals(23, $config->getExtraVacationMinimumAge());
    }

    public function testGetExtraVacationDaysReturnsZeroWhenValueIsNotSet()
    {
        $options = $this->getValidOptions();
        unset($options['extra-day-in-every-x-year']);

        $config = new Config($options);

        $this->assertEquals(0, $config->getExtraVacationDaysInEveryXYear());
    }

    public function testGetExtraVacationDaysInEveryXYear()
    {
        $options = $this->getValidOptions();
        $config = new Config($options);

        $this->assertEquals(45, $config->getExtraVacationDaysInEveryXYear());
    }

    protected function getValidOptions()
    {
        return [
            'minimum-vacation-days' => 12,
            'extra-day-minimum-age' => 23,
            'extra-day-in-every-x-year' => 45,
        ];
    }
}

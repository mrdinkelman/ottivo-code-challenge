<?php

namespace VacationCalculator\Calculator;

/**
 * Class CalculatorException
 *
 * Exception class for Calculator. Usually, this type of Exception might be handled gracefully.
 *
 * @package VacationCalculator\Calculator
 */
class CalculatorException extends \RuntimeException
{
    /**
     * May be thrown in case when Employee entity is not set into Calculator.
     *
     * @return CalculatorException
     */
    public static function employeeIsNotSet()
    {
        return new self('Employee is not set via setter. Please set employee instance first');
    }

    /**
     * May be thrown in case when work interval can not be retrieved on received date
     *
     * @param \DateTime $dateTime
     * @return CalculatorException
     */
    public static function unableToGetWorkInterval(\DateTime $dateTime)
    {
        return new self(sprintf(
            'Unable to get work interval for received date - %s',
            $dateTime->format('Y-m-d')
        ));
    }
}

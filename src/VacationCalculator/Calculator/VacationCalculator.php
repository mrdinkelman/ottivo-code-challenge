<?php

namespace VacationCalculator\Calculator;

use VacationCalculator\Entity\Employee;

/**
 * Class VacationCalculator
 *
 * @package VacationCalculator\Calculator
 */
class VacationCalculator
{
    /** @var Employee|null */
    protected $employee;

    /** @var \DateTime */
    protected $dateTime;

    /** @var Config  */
    protected $config;

    /** @var int|null */
    protected $defaultVacationDaysCount;

    /** @var int|null */
    protected $minimumAge;

    /** @var int|null */
    protected $everyXYear;

    /**
     * VacationCalculator constructor.
     *
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param Employee $employee
     * @return $this
     */
    public function setEmployee(Employee $employee)
    {
        $this->employee = $employee;

        return $this;
    }

    /**
     * Returns employee instance
     *
     * @return Employee
     * @throws CalculatorException
     */
    public function getEmployee()
    {
        if (null === $this->employee) {
            throw CalculatorException::employeeIsNotSet();
        }

        return $this->employee;
    }

    /**
     * Set calculator datetime. In case when not present current date and time will be used.
     *
     * @param \DateTime|null $dateTime
     *
     * @return $this
     */
    public function setDateTime(\DateTime $dateTime = null)
    {
        if ($dateTime) {
            $this->dateTime = $dateTime;
        }

        return $this;
    }

    /**
     * Returns date time which used for calculation
     *
     * @return \DateTime
     */
    public function getDateTime()
    {
        return $this->dateTime ?: new \DateTime();
    }

    /**
     * Calculates vacation days for employee
     *
     * @return int
     *
     * @throws \InvalidArgumentException
     * @throws CalculatorException
     */
    public function calculate()
    {
        if (null === $this->defaultVacationDaysCount) {
            $this->init();
        }

        $employee = $this->getEmployee();

        $vacationDays = $employee->getSpecialDays() ?: $this->defaultVacationDaysCount + $this->getExtraVacationDays();
        $workInterval = $this->getWorkInterval();

        if ($workInterval->y < 1) {
            $vacationDays = $this->getVacationDaysCountForNewBie( $workInterval, $vacationDays);;
        }

        return $vacationDays;
    }

    /**
     * @throws \InvalidArgumentException
     */
    protected function init()
    {
        $this->defaultVacationDaysCount = $this->config->getDefaultVacationDaysCount();
        $this->minimumAge = $this->config->getExtraVacationMinimumAge();
        $this->everyXYear = $this->config->getExtraVacationDaysInEveryXYear();
    }

    /**
     * Returns working interval for current employee
     *
     * @return \DateInterval
     * @throws CalculatorException
     */
    protected function getWorkInterval()
    {
        $dateTime = $this->getDateTime();
        $employee = $this->getEmployee();

        $workInterval = $employee->getWorkInterval($dateTime);

        if (null === $workInterval) {
            throw CalculatorException::unableToGetWorkInterval($dateTime);
        }

        return $workInterval;
    }

    /**
     * Returns amount of vacations days for newbie employees.
     *
     * @param \DateInterval $workInterval
     * @param int           $maxAllowedDays
     *
     * @return int
     */
    protected function getVacationDaysCountForNewBie(\DateInterval $workInterval, $maxAllowedDays)
    {
        return floor($workInterval->m * ($maxAllowedDays / 12));
    }

    /**
     * Returns amount of extra vacation days in $everyXYear from employee $minimumAge
     *
     * @return int
     */
    protected function getExtraVacationDays()
    {
        $employee = $this->getEmployee();
        $age = $employee->getAge($this->getDateTime());

        if (0 === $this->everyXYear || $this->minimumAge > $age) {
            return 0;
        }

        return floor(($age - $this->minimumAge) / $this->everyXYear);
    }
}
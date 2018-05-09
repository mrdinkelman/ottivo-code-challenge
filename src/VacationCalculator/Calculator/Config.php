<?php

namespace VacationCalculator\Calculator;

use Assert\Assert;

/**
 * Class Config
 *
 * Configuration layer for ability to get and control default configuration values.
 *
 * @package VacationCalculator\Calculator
 */
class Config
{
    const KEY_MINIMUM_VACATION_DAYS = 'minimum-vacation-days';
    const KEY_EXTRA_DAY_MINIMUM_AGE = 'extra-day-minimum-age';
    const KEY_EXTRA_DAY_IN_EVERY_X_YEAR = 'extra-day-in-every-x-year';

    private $options;

    /**
     * Config constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    /**
     * Returns default vacation days count as integer
     *
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getDefaultVacationDaysCount()
    {
        Assert::that($this->options)
            ->keyExists(self::KEY_MINIMUM_VACATION_DAYS, 'Minimum vacation days is not set via config')
            ->greaterThan(0, 'Minimum vacation day must be a positive integer');

        return $this->options[self::KEY_MINIMUM_VACATION_DAYS];
    }

    /**
     * Returns minimum age for extra vacation days as integer
     *
     * @return int
     * @throws \InvalidArgumentException
     */
    public function getExtraVacationMinimumAge()
    {
        Assert::that($this->options)
            ->keyExists(self::KEY_EXTRA_DAY_MINIMUM_AGE, 'Minimum age for extra vacation days is not set via config')
            ->greaterThan(0, 'Minimum age for extra vacation days must be a positive integer');

        return $this->options[self::KEY_EXTRA_DAY_MINIMUM_AGE];
    }

    /**
     * Returns how many extra vacations days may be added every X years as integer or 0 in case
     * when this setting is not set
     *
     * @return int
     */
    public function getExtraVacationDaysInEveryXYear()
    {
        if (array_key_exists(self::KEY_EXTRA_DAY_IN_EVERY_X_YEAR, $this->options)) {
            Assert::that($this->options[self::KEY_EXTRA_DAY_IN_EVERY_X_YEAR])
                ->greaterThan(0, 'You need to specify positive integer value for yearly vacation days');

            return $this->options[self::KEY_EXTRA_DAY_IN_EVERY_X_YEAR];
        }

        return 0;
    }
}

<?php

use VacationCalculator\Command;
use VacationCalculator\Calculator;

return [
    // Provides employee application default configuration.
    'employee' => [
        Calculator\Config::KEY_MINIMUM_VACATION_DAYS     => 26,
        Calculator\Config::KEY_EXTRA_DAY_IN_EVERY_X_YEAR => 5,
        Calculator\Config::KEY_EXTRA_DAY_MINIMUM_AGE     => 30,
    ],
    'dependencies' => [
        'factories' => [
            Command\VacationCalculatorCommand::class => Command\VacationCalculatorCommandFactory::class,

            Calculator\Config::class             => Calculator\ConfigFactory::class,
            Calculator\VacationCalculator::class => Calculator\VacationCalculatorFactory::class,
        ],
    ],
    'cli' => [
        'commands' => [
            Command\VacationCalculatorCommand::class,
        ],
    ]
];
<?php

return [
    'logger' => [
        'vacation-calculator' => [
            'file' => __DIR__ . '/../../data/log/vacation-calculator.log',
            'level' => \Monolog\Logger::DEBUG,
        ],
    ],
    'dependencies' => [
        'factories' => [
            \Monolog\Logger::class => \VacationCalculator\Logger\LoggerFactory::class,
        ],
    ],
];

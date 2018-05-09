<?php

return [
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'params' => [
                    'url' =>  'sqlite:////' . __DIR__ . '/../../data/employee.db',
                ],
            ],
        ],
        'driver' => [
            'orm_default' => [
                'class' => \Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain::class,
                'drivers' => [
                    'VacationCalculator\Entity' => 'annotation',
                ],
            ],
            'annotation' => [
                'class' => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                'cache' => 'array',
                'paths' => __DIR__ . '/../../src/VacationCalculator/src/Entity',
            ],
        ],
    ],
    'dependencies' => [
        'factories' => [
            \Doctrine\ORM\EntityManager::class => \ContainerInteropDoctrine\EntityManagerFactory::class,
        ],
    ],
];

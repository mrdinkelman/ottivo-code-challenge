<?php

$container = require 'config/container.php';

/** @var \Doctrine\ORM\EntityManager $entityManager */
$entityManager = $container->get('doctrine.entity_manager.orm_default');

return new \Symfony\Component\Console\Helper\HelperSet([
    'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($entityManager->getConnection()),
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($entityManager),
]);

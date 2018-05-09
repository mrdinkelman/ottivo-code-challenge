<?php

namespace VacationCalculator\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class EmployeeRepository
 *
 * @package VacationCalculator\Repository
 */
class EmployeeRepository extends EntityRepository
{
    /**
     * Finds all employees which working in company in received date
     *
     * @param \DateTime $dateTime
     *
     * @return array
     */
    public function findAllByDateStart(\DateTime $dateTime)
    {
        $queryBuilder = $this->createQueryBuilder('e');
        $queryBuilder
            ->where('e.dateStart <= :date')
            ->setParameter('date', $dateTime->format('Y-m-d H:i:s'));

        return $queryBuilder->getQuery()->getResult();
    }
}

<?php

namespace VacationCalculatorTest\Repository;

use VacationCalculator\Repository\EmployeeRepository;
use VacationCalculator\Entity\Employee;
use Webfactory\Doctrine\ORMTestInfrastructure\ORMInfrastructure;

use PHPUnit\Framework\TestCase;

class EmployeeRepositoryTest extends TestCase
{
    /** @var ORMInfrastructure */
    private $infrastructure;

    /** @var EmployeeRepository */
    private $repository;

    /** @see \PHPUnit_Framework_TestCase::setUp() */
    protected function setUp()
    {
        $this->infrastructure = ORMInfrastructure::createWithDependenciesFor(Employee::class);
        $this->repository = $this->infrastructure->getRepository(Employee::class);
    }

    public function testFindAllByDateStartReturnsZeroResultsWhenNoDataMatchedWithDateTime()
    {
        $dateTime = (new \DateTime())->modify('-1 year');

        $this->infrastructure->import($this->getFixture());
        $entitiesLoadedFromDatabase = $this->repository->findAllByDateStart($dateTime);

        $this->assertCount(0, $entitiesLoadedFromDatabase);
    }

    public function testFindAllByDateStartReturnsDataSet()
    {
        $dateTime = (new \DateTime())->modify('+1 year');
        $fixture = $this->getFixture();

        $this->infrastructure->import($fixture);
        $entitiesLoadedFromDatabase = $this->repository->findAllByDateStart($dateTime);

        $this->assertCount(1, $entitiesLoadedFromDatabase);

        $entityLoadedFromDatabase = $entitiesLoadedFromDatabase[0];
        $this->assertEquals($fixture->getId(), $entityLoadedFromDatabase->getId());
        $this->assertEquals($fixture->getName(), $entityLoadedFromDatabase->getName());
        $this->assertEquals($fixture->getDateStart(), $entityLoadedFromDatabase->getDateStart());
        $this->assertEquals($fixture->getSpecialDays(), $entityLoadedFromDatabase->getSpecialDays());
    }

    protected function getFixture()
    {
        $today = new \DateTime();

        $fixture = new Employee();
        $fixture->setDateStart($today);
        $fixture->setName('John Doe');
        $fixture->setDateOfBirth($today);

        return $fixture;
    }
}

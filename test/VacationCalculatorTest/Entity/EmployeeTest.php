<?php

namespace VacationCalculatorTest\Entity;

use VacationCalculator\Entity\Employee;
use PHPUnit\Framework\TestCase;

class EmployeeTest extends TestCase
{
    public function testGetIdReturnsNullWhenNoIdSet()
    {
        $entity = new Employee();

        $this->assertNull($entity->getId());
    }

    public function testSetName()
    {
        $entity = new Employee();
        $result = $entity->setName('John Doe');

        $this->assertEquals($entity, $result);
    }

    public function testGetName()
    {
        $entity = new Employee();

        $this->assertNull($entity->getName());

        $entity->setName('John Doe');
        $this->assertEquals('John Doe', $entity->getName());
    }

    public function testSetDateOfBirth()
    {
        $datetime = new \DateTime();

        $entity = new Employee();
        $result = $entity->setDateOfBirth($datetime);

        $this->assertEquals($entity, $result);
    }

    public function testGetDateOfBirth()
    {
        $entity = new Employee();

        $this->assertNull($entity->getDateOfBirth());

        $datetime = new \DateTime('1990-01-02 01:02:03');
        $entity->setDateOfBirth($datetime);

        $this->assertEquals('1990-01-02 00:00:00', $entity->getDateOfBirth()->format('Y-m-d H:i:s'));
    }

    public function testSetDateStart()
    {
        $datetime = new \DateTime();

        $entity = new Employee();
        $result = $entity->setDateStart($datetime);

        $this->assertEquals($entity, $result);
    }

    public function testGetDateStart()
    {
        $entity = new Employee();

        $this->assertNull($entity->getDateStart());

        $datetime = new \DateTime('1990-01-02 01:02:03');
        $entity->setDateStart($datetime);

        $this->assertEquals('1990-01-02 00:00:00', $entity->getDateStart()->format('Y-m-d H:i:s'));
    }

    public function testSetSpecialDaysDoNothingWhenSpecialDaysNotPassed()
    {
        $entity = new Employee();
        $result = $entity->setSpecialDays();

        $this->assertEquals($entity, $result);
    }

    public function testSetSpecialDaysThrowsExceptionWhenSpecialDaysInvalid()
    {
        $entity = new Employee();

        $this->expectException(\InvalidArgumentException::class);
        $entity->setSpecialDays(-1);
    }

    public function testSetSpecialDays()
    {
        $entity = new Employee();
        $result = $entity->setSpecialDays(123);

        $this->assertEquals($entity, $result);
    }

    public function testGetSpecialDays()
    {
        $entity = new Employee();

        $this->assertNull($entity->getSpecialDays());

        $entity->setSpecialDays();
        $this->assertNull($entity->getSpecialDays());

        $entity->setSpecialDays(123);
        $this->assertEquals(123, $entity->getSpecialDays());
    }

    public function testGetWorkIntervalReturnsNullWhenStartDateIsNotSet()
    {
        $datetime = new \DateTime();
        $entity = new Employee();

        $this->assertNull($entity->getWorkInterval($datetime));
    }

    public function testGetWorkIntervalReturnsNullWhenStartDateGreaterThanCurrentDate()
    {
        $datetime = new \DateTime('2002-01-01 00:00:00');
        $entity = new Employee();

        $entity->setDateStart($datetime);

        $datetime = new \DateTime('2001-01-01 00:00:00');

        $this->assertNull($entity->getWorkInterval($datetime));
    }

    public function testGetWorkInterval()
    {
        $datetime = new \DateTime('2001-01-01 00:00:00');
        $entity = new Employee();

        $entity->setDateStart($datetime);

        $datetime = new \DateTime('2002-01-01 00:00:00');

        $this->assertInstanceOf(\DateInterval::class, $entity->getWorkInterval($datetime));
    }

    public function testGetAge()
    {
        $datetime = new \DateTime();
        $entity = new Employee();

        $entity->setDateOfBirth($datetime);

        $this->assertEquals(0, $entity->getAge($datetime));

        $datetime = clone $datetime;
        $datetime->modify('+10 years');

        $this->assertEquals(10, $entity->getAge($datetime));

    }

    public function testAsArray()
    {
        $entity = new Employee();
        $entity->setName('John Doe');

        $dateOfBirth = new \DateTime('1990-01-02 12:13:14');
        $entity->setDateOfBirth($dateOfBirth);

        $dateStart = new \DateTime('2001-01-02 01:02:03');
        $entity->setDateStart($dateStart);

        $entity->setSpecialDays(123);

        $this->assertEquals(
            [
                'id' => null,
                'name' => 'John Doe',
                'dateOfBirth' => '1990-01-02',
                'dateStart' => '2001-01-02',
                'specialDays' => 123,
            ],
            $entity->asArray()
        );
    }
}

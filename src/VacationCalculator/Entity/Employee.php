<?php

namespace VacationCalculator\Entity;

use Doctrine\ORM\Mapping as ORM;
use Assert\Assert;

/**
 * Employee
 *
 * @ORM\Table(name="employee")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="VacationCalculator\Repository\EmployeeRepository")
 */
class Employee
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="text", precision=0, scale=0, nullable=false, unique=false)
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateOfBirth", type="date", precision=0, scale=0, nullable=false, unique=false)
     */
    private $dateOfBirth;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateStart", type="date", precision=0, scale=0, nullable=false, unique=false)
     */
    private $dateStart;

    /**
     * @var integer
     *
     * @ORM\Column(name="specialDays", type="integer", precision=0, scale=0, nullable=true, unique=false)
     */
    private $specialDays;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Employee
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set date of birth
     *
     * @param \DateTime $dateOfBirth
     *
     * @return Employee
     */
    public function setDateOfBirth(\DateTime $dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;
        $this->dateOfBirth->setTime(0, 0, 0);

        return $this;
    }

    /**
     * Get date of birth
     *
     * @return \DateTime
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * Set employee start date
     *
     * @param \DateTime $dateStart
     *
     * @return Employee
     */
    public function setDateStart(\DateTime $dateStart)
    {
        $this->dateStart = $dateStart;
        $this->dateStart->setTime(0, 0, 0);

        return $this;
    }

    /**
     * Get date when employee starts to work
     *
     * @return \DateTime
     */
    public function getDateStart()
    {
        return $this->dateStart;
    }

    /**
     * Set amount of special vacation days
     *
     * @param integer|null $specialDays
     *
     * @return Employee
     * @throws \InvalidArgumentException
     */
    public function setSpecialDays($specialDays = null)
    {
        if (empty($specialDays)) {
            return $this;
        }

        Assert::that($specialDays)->greaterOrEqualThan(0, 'Amount of special day is invalid');

        $this->specialDays = $specialDays;

        return $this;
    }

    /**
     * Get amount of special vacation days
     *
     * @return integer
     */
    public function getSpecialDays()
    {
        return $this->specialDays;
    }

    /**
     * Returns interval between first working day and $dateTime. In case when received date will be
     * greater than first working day will return NULL
     *
     * @param \DateTime|null $dateTime
     * @return \DateInterval|null
     */
    public function getWorkInterval(\DateTime $dateTime = null)
    {
        $startDate = $this->getDateStart();

        if (!$startDate) {
            return null;
        }

        // reset start date to first day of next month
        if ((int) $startDate->format('d') != 1) {
            $month = $startDate->format('m');
            $month += 1;

            $startDate->setDate($startDate->format('Y'), $month, 1);
            $startDate->setTime(0, 0, 0);
        }

        $currentDate = $dateTime ? clone ($dateTime): new \DateTime();
        $currentDate->modify('+1 day');
        $currentDate->setTime(0, 0, 0);

        if ($startDate > $currentDate) {
            return null;
        }

        return $currentDate->diff($startDate);
    }

    /**
     * Returns employee age
     *
     * @param \DateTime|null $dateTime
     * @return int
     */
    public function getAge(\DateTime $dateTime = null)
    {
        $currentDate = $dateTime ?: new \DateTime();
        $currentDate->setTime(0, 0, 0);

        $birthDay = $this->getDateOfBirth() ?: $currentDate;

        if ($birthDay >= $currentDate) {
            return 0;
        }

        return $birthDay->diff($currentDate)->y;
    }

    /**
     * Returns Employee entity as array
     *
     * @return array
     */
    public function asArray()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'dateOfBirth' => $this->getDateOfBirth()->format('Y-m-d'),
            'dateStart' => $this->getDateStart()->format('Y-m-d'),
            'specialDays' => $this->getSpecialDays(),
        ];
    }
}

<?php

namespace VacationCalculator\Command;

use VacationCalculator\Calculator\CalculatorException;
use VacationCalculator\Entity\Employee;
use VacationCalculator\Calculator\VacationCalculator;
use Assert\Assert;
use Assert\AssertionFailedException;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class VacationCalculatorCommand
 *
 * Console command for calculate number of vacation days in year
 *
 * @package VacationCalculator\Command
 */
class VacationCalculatorCommand extends Command
{
    const ARGUMENT_YEAR = 'year';
    const VALUE_MIN_YEAR = 1900;

    const CODE_SUCCESS = 0;
    const CODE_INVALID_ARGUMENT = 1;
    const CODE_UNABLE_TO_CALCULATE = 2;
    const CODE_NOT_CONFIGURED = 3;

    /** @var VacationCalculator */
    private $calculator;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var LoggerInterface  */
    private $logger;

    /**
     * GreetCommand constructor.
     *
     * @param VacationCalculator     $calculator
     * @param EntityManagerInterface $entityManager
     * @param LoggerInterface        $logger
     */
    public function __construct(
        VacationCalculator $calculator, EntityManagerInterface $entityManager, LoggerInterface $logger
    ) {
        $this->calculator = $calculator;
        $this->entityManager = $entityManager;
        $this->logger = $logger;

        parent::__construct();
    }

    /**
     * Configures the command
     */
    protected function configure()
    {
        $currentYear = date('Y', time());

        $this
            ->setName('vacation-calculator:employee:calculate')
            ->setDescription('Calculates number of vacation days for the end of received year')
            ->addArgument(
                self::ARGUMENT_YEAR,
                InputArgument::OPTIONAL,
                sprintf('Year. Example value is %d', $currentYear),
                $currentYear
            );
    }

    /**
     * Executes console command. Return zero code in success (no employees in received year or
     * calculation has been processed successfully) or non-zero codes if fails
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        if (!$this->canProcess($input)) {
            $io->error('Invalid input arguments received. Please check arguments and try again');

            return self::CODE_INVALID_ARGUMENT;
        }

        try {
            $vacationDaysList = $this->calculateVacationDays($input);

        } catch (CalculatorException $exception) {
            $this->logger->error('An exception was occurred during calculation process', [
                'name' => $this->getName(),
                'exceptionMessage' => $exception->getMessage(),
            ]);

            $io->error('Unable to calculate vacation days. Error: ' . $exception->getMessage());

            return self::CODE_UNABLE_TO_CALCULATE;

        } catch (AssertionFailedException $exception) {
            $this->logger->error('Configuration error', [
                'name' => $this->getName(),
                'exceptionMessage' => $exception->getMessage(),
            ]);

            $io->error('CLI command is not configured. Error: ' . $exception->getMessage());

            return self::CODE_NOT_CONFIGURED;
        }

        if (empty($vacationDaysList)) {
            $io->success('There are not employees in company in specific year');

            return self::CODE_SUCCESS;
        }

        $io->success(sprintf(
            'A list of employees and their vacation days for end of %d year',
            $this->getDatetime($input)->format('Y'))
        );
        $io->table($this->getTableHeaders(), $vacationDaysList);

        $this->logger->info('CLI command has been executed successfully', [
            'name' => $this->getName(),
            'vacationDaysList' => $vacationDaysList,
        ]);

        return self::CODE_SUCCESS;
    }

    /**
     * Returns TRUE in case when input arguments are correct and command can be executed
     *
     * @param InputInterface $input
     *
     * @return bool
     */
    private function canProcess(InputInterface $input)
    {
        $datetime = $this->getDatetime($input);
        $year = $datetime->format('Y');

        try {
            Assert::that($year)
                ->greaterThan(self::VALUE_MIN_YEAR, 'Invalid year value received');

        } catch (\InvalidArgumentException $exception) {
            $exceptionMessage = $exception->getMessage();

            $this->logger->error('Exception due executing CLI command has been triggered', [
                'name' => $this->getName(),
                'year' => $year,
                'minYear' => self::VALUE_MIN_YEAR,
                'exceptionMessage' => $exceptionMessage,
            ]);

            return false;
        }

        return true;
    }

    /**
     * Returns DateTime object based on received year from input argument.
     * Value in object matches to last day of the year.
     *
     * @param InputInterface $input
     * @return \DateTime
     */
    private function getDatetime(InputInterface $input)
    {
        $dateTime = new \DateTime();
        $dateTime->setDate($input->getArgument(self::ARGUMENT_YEAR), 12, 31);
        $dateTime->setTime(23, 59, 59);

        return $dateTime;
    }

    /**
     * Returns result table headers as list
     *
     * @return array
     */
    private function getTableHeaders()
    {
        return [
            'ID',
            'Name',
            'Birthday',
            'Started from',
            'Special Vacation days value',
            'Age',
            'Vacation days',
        ];
    }

    /**
     * Calculates vacation days for employees and return them as list
     *
     * @param InputInterface $input
     *
     * @return array
     * @throws \InvalidArgumentException
     * @throws CalculatorException
     */
    private function calculateVacationDays(InputInterface $input)
    {
        $dateTime = $this->getDatetime($input);

        /** @var \VacationCalculator\Repository\EmployeeRepository$employeeRepository */
        $employeeRepository = $this->entityManager->getRepository(Employee::class);
        $employees = $employeeRepository->findAllByDateStart($dateTime);

        $result = [];
        $this->calculator->setDateTime($dateTime);

        /** @var \VacationCalculator\Entity\Employee $employee */
        foreach ($employees as $employee) {
            $this->calculator->setEmployee($employee);

            $result[] = array_merge(
                $employee->asArray(),
                [
                    $employee->getAge($dateTime),
                    $this->calculator->calculate()
                ]
            );
        }

        return $result;
    }
}

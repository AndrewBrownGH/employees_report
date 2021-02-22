<?php declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Employee;
use App\Entity\TimeReport;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EmployeeAndReportFixtures extends Fixture
{
    private const NAMES = ['Andrew', 'John', 'Alex', 'Pavel', 'Vera'];

    private const COUNT_EMPLOYEES = 30;

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < self::COUNT_EMPLOYEES; $i++) {
            $employee = new Employee($this->getRandomName());
            $manager->persist($employee);

            foreach ($this->getEmployeeReports($employee, mt_rand(3, 5)) as $report)
            {
                $manager->persist($report);
            }
        }

        $manager->flush();
    }

    private function getRandomName(): string
    {
        return self::NAMES[array_rand(self::NAMES)];
    }

    private function getRandomHours(): float
    {
        return mt_rand(1, 80) / 10;
    }

    private function getRandomDate(string $startDate, string $endDate, string $format = 'Y-m-d H:i:s'): DateTime
    {
        $startDate = strtotime($startDate);
        $endDate = strtotime($endDate);

        $randomDate = date($format, mt_rand($startDate, $endDate));

        return new DateTime($randomDate); //todo throw exception
    }

    private function getEmployeeReports(Employee $employee, int $count = 1): array
    {
        $reports = [];

        while ($count--) {
            $reports[] = new TimeReport(
                $employee,
                $this->getRandomHours(),
                $this->getRandomDate('2021-02-16', '2021-02-23', 'Y-m-d')
            );
        }

        return $reports;
    }
}

<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\TimeReport;
use Doctrine\ORM\EntityManager;

class TimeReportService
{
    private EntityManager $entityManager;

    private const DAYS = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"];

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function receiveReport()
    {
        $bestEmployees = $this->entityManager->getRepository(TimeReport::class)->findBestEmployeesByCurrentWeek(3);

        $formattedReports = $this->createFormattedReports($bestEmployees);

        return $formattedReports;
    }

    private function createFormattedReports(array $reports): array
    {
        $formattedReports = [];

        $topCount = 3;
        $maxDaySize = 9;
        $start = 0;
        $maxNameSize = 0;
        $maxTimeSize = 0;

        for ($i = 0; $i < count($reports); $i++) {
            if (strlen($reports[$i]['name']) > $maxNameSize) {
                $maxNameSize = strlen($reports[$i]['name']);
            }

            if (strlen($reports[$i]['sum_hours']) > $maxTimeSize) {
                $maxTimeSize = strlen($reports[$i]['sum_hours']);
            }
        }
        $maxCellSize = $maxNameSize + $maxTimeSize;

        for ($i = 0; $i < count(self::DAYS); $i++) {
            $row = $this->getFormattedDay(self::DAYS[$i]);

            for ($j = $start; $j < $start + $topCount; $j++) {
                if ($j > count($reports)) {
                    break;
                }

                if ($reports[$j]['day_name'] === self::DAYS[$i]) {
                    $row .= $reports[$j]['name'] . " (" . $this->getRoundHours($reports[$j]['sum_hours']) . ")";

                    $currentCellSize = strlen($reports[$j]['name']) + strlen($reports[$j]['sum_hours']);
                    $row .= str_repeat(' ', $maxCellSize - $currentCellSize);
                    $row .= " | ";

                    $start++;
                } else {
                    break;
                }
            }

            $formattedReports[] = $row;
        }

        return $formattedReports;
    }

    private function getRoundHours(string $hours): string
    {
        return number_format((float)$hours, 2, '.', '');
    }

    private function getFormattedDay($weekDay)
    {
        $maxDaySize = 9;

        $day = " | " . $weekDay;
        $day .= str_repeat(' ', $maxDaySize - strlen($weekDay));
        $day .= "| ";

        return $day;
    }
}
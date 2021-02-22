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
        $start = 0;

        $maxCellSize = $this->getMaxCellSize($reports);

        for ($i = 0; $i < count(self::DAYS); $i++) {
            $row = $this->getFormattedDay(self::DAYS[$i]);
            $count = 0;

            for ($j = $start; $j < $start + $topCount; $j++) {
                if ($j >= count($reports)) {
                    if ($count === 0) {
                        $row .= "No Results";
                    }
                    break;
                }

                if ($reports[$j]['day_name'] === self::DAYS[$i]) {
                    $row .= $this->getFormattedCell($reports[$j]['name'], $reports[$j]['sum_hours'], $maxCellSize);

                    $start++;
                    $count++;
                } else {
                    if ($count == 0) {
                        $row .= "No Results";
                        break;
                    }
                    for ($k = 0; $k < $topCount - $count; $k++) {
                        $row .= str_repeat(' ', $maxCellSize + 3);
                        $row .= " | ";
                    }
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
        $day .= " | ";

        return $day;
    }

    private function getFormattedCell(string $name, string $hours, $maxCellSize)
    {
        $hours = $this->getRoundHours($hours);
        $cell = $name . " (" . $hours . ")";

        $currentCellSize = strlen($name) + strlen($hours);

        $cell .= str_repeat(' ', $maxCellSize - $currentCellSize);
        $cell .= " | ";

        return $cell;
    }

    private function getMaxCellSize(array $reports)
    {
        $maxNameSize = $maxTimeSize = 0;

        for ($i = 0; $i < count($reports); $i++) {
            if (strlen($reports[$i]['name']) > $maxNameSize) {
                $maxNameSize = strlen($reports[$i]['name']);
            }

            if (strlen($this->getRoundHours($reports[$i]['sum_hours'])) > $maxTimeSize) {
                $maxTimeSize = strlen($this->getRoundHours($reports[$i]['sum_hours']));
            }
        }

        return $maxNameSize + $maxTimeSize;
    }
}
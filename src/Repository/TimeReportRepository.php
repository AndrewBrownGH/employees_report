<?php

namespace App\Repository;

use App\Entity\TimeReport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TimeReport|null find($id, $lockMode = null, $lockVersion = null)
 * @method TimeReport|null findOneBy(array $criteria, array $orderBy = null)
 * @method TimeReport[]    findAll()
 * @method TimeReport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TimeReportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TimeReport::class);
    }

    public function findBestEmployeesByCurrentWeek(int $limit = 3)
    {
        $sql = "select *
                from (
                         select employees.name,
                                DAYNAME(time_reports.date) as day_name,
                                SUM(time_reports.hours) as sum_hours,
                                ROW_NUMBER() OVER(partition by time_reports.date order by time_reports.date, SUM(time_reports.hours)
                                    desc) AS number_report
                         from employees
                                  join time_reports on employees.id = time_reports.employee_id
                         group by time_reports.date, employees.id
                         order by time_reports.date, sum_hours desc
                     ) result
                where result.number_report <= 3
                ";

        $connection = $this->getEntityManager()->getConnection();

        $statement = $connection->prepare($sql);
        $statement->execute();

        return $statement->fetchAllAssociative();
    }

    public function findBestEmployeesByDate(string $date, int $limit = 3)
    {
        return $this->createQueryBuilder('report')
            ->select('IDENTITY(report.employee) AS employee_id', 'AVG(report.hours) AS average_hours')
            ->where('report.date = :date')
            ->groupBy('report.employee')
            ->orderBy('average_hours', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->execute(['date' => $date]);
    }
}

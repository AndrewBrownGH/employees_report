<?php declare(strict_types=1);

namespace App\Entity;

use App\Repository\TimeReportRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TimeReportRepository::class)
 * @ORM\Table(name="time_reports")
 */
class TimeReport
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity=Employee::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private Employee $employee;

    /**
     * @ORM\Column(type="float")
     * @Assert\Range(min="0", max="8");
     */
    private float $hours;

    /**
     * @ORM\Column(type="date")
     */
    private DateTimeInterface $date;

    public function __construct(Employee $employee, float $hours, DateTimeInterface $dateTime)
    {
        $this->employee = $employee;
        $this->hours = $hours;
        $this->date = $dateTime;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmployee(): Employee
    {
        return $this->employee;
    }

    public function setEmployee(Employee $employee): self
    {
        $this->employee = $employee;

        return $this;
    }

    public function getHours(): float
    {
        return $this->hours;
    }

    public function setHours(float $hours): self
    {
        $this->hours = $hours;

        return $this;
    }

    public function getDate(): DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}

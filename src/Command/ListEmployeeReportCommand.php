<?php declare(strict_types=1);

namespace App\Command;

use App\Service\TimeReportService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ListEmployeeReportCommand extends Command
{
    private TimeReportService $timeReportService;

    protected static $defaultName = 'report:top-employees';
    protected static string $defaultDescription = 'Display a list of top 3 best employees for each day of the current week';
    protected static string $fullDescription = 'This command allows you to fetch the information about the employees\' time reports for each day of the week calculate the top 3 employees who have the highest average number of working hours reported on the corresponding weekday.';

    public function __construct(TimeReportService $timeReportService)
    {
        $this->timeReportService = $timeReportService;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->setHelp(self::$fullDescription);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $employeesReports = $this->timeReportService->receiveReport();

        foreach ($employeesReports as $employeesReport)
        {
            $output->writeln($employeesReport);
        }

        return Command::SUCCESS;
    }
}

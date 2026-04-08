<?php

namespace App\Infra\Ports\Command;
//
use App\App\Contracts\Database\ConnectionInterface;
use App\App\UseCase\Report\Generate\GenerateReportUseCase;
use App\App\UseCase\Report\Generate\Input\GenerateReportInput;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:consumer:report',
    description: 'Consume report queue and process reports'
)]
class GenerateReportCommand extends Command
{
    public function __construct(
        private readonly GenerateReportUseCase $generateReportUseCase,
        private readonly ConnectionInterface $connection
    ) {
        parent::__construct();
    }

    /**
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $output->writeln('<info>Report consumer started...</info>');
        $generateReportConsumer = new GenerateReportConsumer();
        $generateReportConsumer->listen(function (array $payload) use ($output) {
            $this->connection->clear();
            $generateReportInput = new GenerateReportInput($payload['process_id']);
            $result = $this->generateReportUseCase->execute($generateReportInput);
            if ($result->getCode() >= 400) {
                $output->writeln('<error>Failed to process report: ' . json_encode($result->getData()) . '</error>');
            }
        });
        $output->writeln('<info>Report consumer Finished...</info>');
        return Command::SUCCESS;
    }
}
<?php

namespace App\Infra\Ports\Command;

use App\Infra\Adapters\Database\ConnectionDoctrine;
use App\Infra\Mappers\ReportStatus;
use App\Infra\Mappers\Zodiac;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:seed',
    description: 'Seed required data (zodiac signs and report statuses)'
)]
class SeedCommand extends Command
{
    public function __construct(
        private readonly ConnectionDoctrine $connection
    ) {
        parent::__construct();
    }

    /**
     * @throws \DateMalformedStringException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $entityManager = $this->connection->getEntityManager();

        if ($this->hasData($entityManager, Zodiac::class)) {
            $output->writeln('<comment>Zodiac data already seeded, skipping.</comment>');
        } else {
            $this->seedZodiac($entityManager);
            $output->writeln('<info>Zodiac signs seeded.</info>');
        }

        if ($this->hasData($entityManager, ReportStatus::class)) {
            $output->writeln('<comment>Report status data already seeded, skipping.</comment>');
        } else {
            $this->seedReportStatus($entityManager);
            $output->writeln('<info>Report statuses seeded.</info>');
        }

        $output->writeln('<info>Seed completed.</info>');
        return Command::SUCCESS;
    }

    private function hasData($entityManager, string $entity): bool
    {
        $count = $entityManager->createQueryBuilder()
            ->select('COUNT(e)')
            ->from($entity, 'e')
            ->getQuery()
            ->getSingleScalarResult();
        return (int) $count > 0;
    }

    /**
     * @throws \DateMalformedStringException
     */
    private function seedZodiac($entityManager): void
    {
        $signs = [
            ['Aries',       '2000-03-21', '2000-04-19'],
            ['Taurus',      '2000-04-20', '2000-05-20'],
            ['Gemini',      '2000-05-21', '2000-06-20'],
            ['Cancer',      '2000-06-21', '2000-07-22'],
            ['Leo',         '2000-07-23', '2000-08-22'],
            ['Virgo',       '2000-08-23', '2000-09-22'],
            ['Libra',       '2000-09-23', '2000-10-22'],
            ['Scorpio',     '2000-10-23', '2000-11-21'],
            ['Sagittarius', '2000-11-22', '2000-12-21'],
            ['Capricorn',   '2000-12-22', '2000-01-19'],
            ['Aquarius',    '2000-01-20', '2000-02-18'],
            ['Pisces',      '2000-02-19', '2000-03-20'],
        ];
        foreach ($signs as [$sign, $start, $end]) {
            $zodiac = new Zodiac();
            $zodiac
                ->setSign($sign)
                ->setStartDate(new \DateTime($start))
                ->setEndDate(new \DateTime($end));
            $entityManager->persist($zodiac);
        }
        $entityManager->flush();
    }

    private function seedReportStatus($entityManager): void
    {
        $statuses = [
            [1, 'PENDING'],
            [2, 'PROCESSING'],
            [3, 'COMPLETED'],
            [4, 'FAILURE'],
        ];
        foreach ($statuses as [$id, $description]) {
            $status = new ReportStatus();
            $status->setId($id)->setDescription($description);
            $entityManager->persist($status);
        }
        $entityManager->flush();
    }
}

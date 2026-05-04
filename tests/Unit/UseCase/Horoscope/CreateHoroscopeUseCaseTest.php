<?php

namespace App\Tests\Unit\UseCase\Horoscope;

use App\App\Contracts\Database\ConnectionInterface;
use App\App\Contracts\Repository\HoroscopeRepositoryInterface;
use App\App\UseCase\Horoscope\Create\CreateUseCase;
use App\App\UseCase\Horoscope\Create\Input\CreateHoroscopeInput;
use App\Domain\Exceptions\InvalidParamsException;
use App\Domain\Exceptions\RepositoryException;
use PHPUnit\Framework\TestCase;

class CreateHoroscopeUseCaseTest extends TestCase
{
    private HoroscopeRepositoryInterface $horoscopeRepository;
    private ConnectionInterface $connection;
    private CreateUseCase $useCase;

    private function buildMessages(): array
    {
        $signs = [
            'uuid-aries', 'uuid-taurus', 'uuid-gemini', 'uuid-cancer',
            'uuid-leo', 'uuid-virgo', 'uuid-libra', 'uuid-scorpio',
            'uuid-sagittarius', 'uuid-capricorn', 'uuid-aquarius', 'uuid-pisces',
        ];

        return array_map(fn(string $id, int $i) => [
            'message' => "Horoscope message $i",
            'luck_number' => $i + 1,
            'zodiac_id' => $id,
        ], $signs, range(0, 11));
    }

    protected function setUp(): void
    {
        $this->horoscopeRepository = $this->createMock(HoroscopeRepositoryInterface::class);
        $this->connection = $this->createMock(ConnectionInterface::class);

        $this->useCase = new CreateUseCase(
            horoscopeRepository: $this->horoscopeRepository,
            connection: $this->connection
        );
    }

    public function testCreateHoroscopeSuccess(): void
    {
        $input = CreateHoroscopeInput::fromArray(
            $this->buildMessages(),
            new \DateTime('2025-01-06'),
            new \DateTime('2025-01-12')
        );

        $this->connection->expects($this->once())->method('begin');
        $this->connection->expects($this->once())->method('commit');
        $this->connection->expects($this->never())->method('rollback');

        $this->horoscopeRepository
            ->expects($this->exactly(12))
            ->method('create');

        $output = $this->useCase->execute($input);

        $this->assertEquals(201, $output->getCode());
    }

    public function testCreateHoroscopeRollbackOnRepositoryFailure(): void
    {
        $input = CreateHoroscopeInput::fromArray(
            $this->buildMessages(),
            new \DateTime('2025-01-06'),
            new \DateTime('2025-01-12')
        );

        $this->connection->expects($this->once())->method('begin');
        $this->connection->expects($this->never())->method('commit');
        $this->connection->expects($this->once())->method('rollback');

        $this->horoscopeRepository
            ->expects($this->once())
            ->method('create')
            ->willThrowException(new RepositoryException('DB error'));

        $output = $this->useCase->execute($input);

        $this->assertEquals(500, $output->getCode());
    }

    public function testCreateHoroscopeInvalidInputLessThan12(): void
    {
        $this->expectException(InvalidParamsException::class);

        CreateHoroscopeInput::fromArray(
            [['message' => 'only one', 'luck_number' => 1, 'zodiac_id' => 'uuid']],
            new \DateTime('2025-01-06'),
            new \DateTime('2025-01-12')
        );
    }

    public function testCreateHoroscopeRollbackOnCommitFailure(): void
    {
        $input = CreateHoroscopeInput::fromArray(
            $this->buildMessages(),
            new \DateTime('2025-01-06'),
            new \DateTime('2025-01-12')
        );

        $this->connection->expects($this->once())->method('begin');
        $this->connection->expects($this->once())->method('commit')
            ->willThrowException(new \Exception('Deadlock detected'));
        $this->connection->expects($this->once())->method('rollback');

        $this->horoscopeRepository
            ->expects($this->exactly(12))
            ->method('create');

        $output = $this->useCase->execute($input);

        $this->assertEquals(500, $output->getCode());
        $this->assertEquals(['exception' => 'Deadlock detected'], $output->getData());
    }
}

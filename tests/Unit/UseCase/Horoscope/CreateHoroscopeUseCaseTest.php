<?php

namespace App\Tests\Unit\UseCase\Horoscope;

use App\App\Contracts\Database\ConnectionInterface;
use App\App\Contracts\Repository\HoroscopeRepositoryInterface;
use App\App\Contracts\Validation\ValidationUserPermissionInterface;
use App\App\UseCase\Horoscope\Create\CreateHoroscopeUseCase;
use App\App\UseCase\Horoscope\Create\Input\CreateHoroscopeInput;
use App\Domain\Exceptions\ForbiddenException;
use App\Domain\Exceptions\InvalidParamsException;
use App\Domain\Exceptions\RepositoryException;
use PHPUnit\Framework\TestCase;

class CreateHoroscopeUseCaseTest extends TestCase
{
    private HoroscopeRepositoryInterface $horoscopeRepository;
    private ValidationUserPermissionInterface $validationUserPermission;
    private ConnectionInterface $connection;
    private CreateHoroscopeUseCase $useCase;

    private function buildInput(): CreateHoroscopeInput
    {
        $signs = [
            'uuid-aries', 'uuid-taurus', 'uuid-gemini', 'uuid-cancer',
            'uuid-leo', 'uuid-virgo', 'uuid-libra', 'uuid-scorpio',
            'uuid-sagittarius', 'uuid-capricorn', 'uuid-aquarius', 'uuid-pisces',
        ];

        $messages = array_map(fn(string $id, int $i) => [
            'message' => "Horoscope message $i",
            'luck_number' => $i + 1,
            'zodiac_id' => $id,
        ], $signs, range(0, 11));

        return CreateHoroscopeInput::fromArray([
            'messages' => $messages,
            'start_date' => '2025-01-06',
            'end_date' => '2025-01-12',
        ]);
    }

    protected function setUp(): void
    {
        $this->horoscopeRepository = $this->createMock(HoroscopeRepositoryInterface::class);
        $this->validationUserPermission = $this->createMock(ValidationUserPermissionInterface::class);
        $this->connection = $this->createMock(ConnectionInterface::class);

        $this->useCase = new CreateHoroscopeUseCase(
            horoscopeRepository: $this->horoscopeRepository,
            validationUserPermission: $this->validationUserPermission,
            connection: $this->connection
        );
    }

    public function testCreateHoroscopeSuccess(): void
    {
        $input = $this->buildInput();

        $this->validationUserPermission->expects($this->once())->method('validate');
        $this->connection->expects($this->once())->method('begin');
        $this->connection->expects($this->once())->method('commit');
        $this->connection->expects($this->never())->method('rollback');

        $this->horoscopeRepository
            ->expects($this->exactly(12))
            ->method('create');

        $output = $this->useCase->execute($input, 'uuid-user-123');

        $this->assertEquals(201, $output->getCode());
    }

    public function testCreateHoroscopeRollbackOnRepositoryFailure(): void
    {
        $input = $this->buildInput();

        $this->validationUserPermission->expects($this->once())->method('validate');
        $this->connection->expects($this->once())->method('begin');
        $this->connection->expects($this->never())->method('commit');
        $this->connection->expects($this->once())->method('rollback');

        $this->horoscopeRepository
            ->expects($this->once())
            ->method('create')
            ->willThrowException(new RepositoryException('DB error'));

        $output = $this->useCase->execute($input, 'uuid-user-123');

        $this->assertEquals(500, $output->getCode());
    }

    public function testCreateHoroscopeInvalidInputLessThan12(): void
    {
        $this->expectException(InvalidParamsException::class);

        CreateHoroscopeInput::fromArray([
            'messages' => [['message' => 'only one', 'luck_number' => 1, 'zodiac_id' => 'uuid']],
            'start_date' => '2025-01-06',
            'end_date' => '2025-01-12',
        ]);
    }

    public function testCreateHoroscopeRollbackOnCommitFailure(): void
    {
        $input = $this->buildInput();

        $this->validationUserPermission->expects($this->once())->method('validate');
        $this->connection->expects($this->once())->method('begin');
        $this->connection->expects($this->once())->method('commit')
            ->willThrowException(new \Exception('Deadlock detected'));
        $this->connection->expects($this->once())->method('rollback');

        $this->horoscopeRepository
            ->expects($this->exactly(12))
            ->method('create');

        $output = $this->useCase->execute($input, 'uuid-user-123');

        $this->assertEquals(500, $output->getCode());
        $this->assertEquals(['exception' => 'Deadlock detected'], $output->getData());
    }

    public function testCreateHoroscopeForbiddenPermission(): void
    {
        $input = $this->buildInput();

        $this->validationUserPermission
            ->expects($this->once())
            ->method('validate')
            ->willThrowException(new ForbiddenException('User does not have permission'));

        $this->connection->expects($this->never())->method('begin');

        $output = $this->useCase->execute($input, 'uuid-user-123');

        $this->assertEquals(403, $output->getCode());
    }
}

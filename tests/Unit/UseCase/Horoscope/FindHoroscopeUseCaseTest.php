<?php

namespace App\Tests\Unit\UseCase\Horoscope;

use App\App\Contracts\Database\MemoryInterface;
use App\App\Contracts\Repository\HoroscopeRepositoryInterface;
use App\App\UseCase\Horoscope\Find\FindHoroscopeUseCase;
use App\App\UseCase\Horoscope\Find\Input\FindHoroscopeInput;
use App\Domain\Entity\Horoscope;
use App\Domain\Entity\Zodiac;
use App\Domain\Exceptions\NotFoundException;
use App\Domain\Exceptions\RepositoryException;
use PHPUnit\Framework\TestCase;

class FindHoroscopeUseCaseTest extends TestCase
{
    private HoroscopeRepositoryInterface $horoscopeRepository;
    private MemoryInterface $memory;
    private FindHoroscopeUseCase $useCase;

    protected function setUp(): void
    {
        $this->horoscopeRepository = $this->createMock(HoroscopeRepositoryInterface::class);
        $this->memory = $this->createMock(MemoryInterface::class);
        $this->memory->method('get')->willReturn(null);
        $this->useCase = new FindHoroscopeUseCase(
            horoscopeRepository: $this->horoscopeRepository,
            memory: $this->memory
        );
    }

    public function testFindHoroscopeSuccess(): void
    {
        $input = FindHoroscopeInput::fromArray('uuid-zodiac-aries');

        $this->horoscopeRepository
            ->expects($this->once())
            ->method('find')
            ->willReturn(Horoscope::fromPrimitives(
                'uuid-horoscope-1',
                new \DateTime('2025-01-06'),
                new \DateTime('2025-01-12'),
                'Great week ahead',
                7,
                Zodiac::fromPrimitives('uuid-zodiac-aries', 'Aries')
            ));

        $output = $this->useCase->execute($input);

        $this->assertEquals(200, $output->getCode());
        $this->assertEquals('Aries', $output->getData()['sign']);
        $this->assertEquals('Great week ahead', $output->getData()['message']);
        $this->assertEquals(7, $output->getData()['luck_number']);
    }

    public function testFindHoroscopeNotFound(): void
    {
        $input = FindHoroscopeInput::fromArray('uuid-zodiac-unknown');

        $this->horoscopeRepository
            ->expects($this->once())
            ->method('find')
            ->willThrowException(new NotFoundException('Horoscope not found'));

        $output = $this->useCase->execute($input);

        $this->assertEquals(404, $output->getCode());
    }

    public function testFindHoroscopeRepositoryFailure(): void
    {
        $input = FindHoroscopeInput::fromArray('uuid-zodiac-aries');

        $this->horoscopeRepository
            ->expects($this->once())
            ->method('find')
            ->willThrowException(new RepositoryException('DB error'));

        $output = $this->useCase->execute($input);

        $this->assertEquals(500, $output->getCode());
    }
}

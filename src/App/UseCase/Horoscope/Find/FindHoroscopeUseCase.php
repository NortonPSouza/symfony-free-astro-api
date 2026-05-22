<?php

namespace App\App\UseCase\Horoscope\Find;

use App\App\Contracts\Database\MemoryInterface;
use App\App\Contracts\Repository\HoroscopeRepositoryInterface;
use App\App\UseCase\Horoscope\Find\Input\FindHoroscopeInput;
use App\App\UseCase\Horoscope\Find\Output\FindHoroscopeOutput;
use App\Domain\Builder\HoroscopeBuilder;
use App\Domain\Entity\Zodiac;
use App\Domain\Exceptions\GenericException;

readonly class FindHoroscopeUseCase
{
    private const int CACHE_TTL = 86400; // 24h
    public function __construct(
        private HoroscopeRepositoryInterface $horoscopeRepository,
        private MemoryInterface $memory
    )
    {
    }

    public function execute(FindHoroscopeInput $input): FindHoroscopeOutput
    {
        try {
            $cacheKey = "horoscope:{$input->getZodiacId()}";
            $cached = $this->memory->get($cacheKey);
            if ($cached) {
                return FindHoroscopeOutput::success(json_decode($cached, true));
            }
            $horoscopeDomain = new HoroscopeBuilder()
                ->withZodiac(Zodiac::fromPrimitives($input->getZodiacId(), ''))
                ->build();
            $horoscopeFound = $this->horoscopeRepository->find($horoscopeDomain);
            $horoscope = [
                'id' => $horoscopeFound->getId(),
                'sign' => $horoscopeFound->getZodiac()->getSign(),
                'message' => $horoscopeFound->getMessage(),
                'luck_number' => $horoscopeFound->getLuckNumber(),
                'start_date' => $horoscopeFound->getStartDate()->format('Y-m-d'),
                'end_date' => $horoscopeFound->getEndDate()->format('Y-m-d'),
            ];
            $this->memory->set($cacheKey, json_encode($horoscope), self::CACHE_TTL);
            return FindHoroscopeOutput::success($horoscope);
        } catch (GenericException $exception) {
            return FindHoroscopeOutput::failure($exception->getStatusCode(), $exception->getData());
        }
    }
}

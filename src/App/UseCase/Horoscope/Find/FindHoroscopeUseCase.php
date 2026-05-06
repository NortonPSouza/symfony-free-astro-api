<?php

namespace App\App\UseCase\Horoscope\Find;

use App\App\Contracts\Repository\HoroscopeRepositoryInterface;
use App\App\UseCase\Horoscope\Find\Input\FindHoroscopeInput;
use App\App\UseCase\Horoscope\Find\Output\FindHoroscopeOutput;
use App\Domain\Builder\HoroscopeBuilder;
use App\Domain\Entity\Zodiac;
use App\Domain\Exceptions\GenericException;

readonly class FindHoroscopeUseCase
{
    public function __construct(
        private HoroscopeRepositoryInterface $horoscopeRepository
    )
    {
    }

    public function execute(FindHoroscopeInput $input): FindHoroscopeOutput
    {
        try {
            $horoscopeDomain = new HoroscopeBuilder()
                ->withZodiac(Zodiac::fromPrimitives($input->getZodiacId(), ''))
                ->build();
            $horoscope = $this->horoscopeRepository->find($horoscopeDomain);
            return FindHoroscopeOutput::success([
                'id' => $horoscope->getId(),
                'sign' => $horoscope->getZodiac()->getSign(),
                'message' => $horoscope->getMessage(),
                'luck_number' => $horoscope->getLuckNumber(),
                'start_date' => $horoscope->getStartDate()->format('Y-m-d'),
                'end_date' => $horoscope->getEndDate()->format('Y-m-d'),
            ]);
        } catch (GenericException $exception) {
            return FindHoroscopeOutput::failure($exception->getStatusCode(), $exception->getData());
        }
    }
}

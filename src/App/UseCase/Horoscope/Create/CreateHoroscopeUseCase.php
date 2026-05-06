<?php

namespace App\App\UseCase\Horoscope\Create;

use App\App\Contracts\Database\ConnectionInterface;
use App\App\Contracts\Repository\HoroscopeRepositoryInterface;
use App\App\Contracts\Validation\ValidationUserPermissionInterface;
use App\App\UseCase\Horoscope\Create\Input\CreateHoroscopeInput;
use App\App\UseCase\Horoscope\Create\Output\CreateHoroscopeOutput;
use App\Domain\Builder\HoroscopeBuilder;
use App\Domain\Entity\Zodiac;
use App\Domain\Exceptions\GenericException;
use App\Domain\Types\PermissionType;

readonly class CreateHoroscopeUseCase
{
    public function __construct(
        private HoroscopeRepositoryInterface $horoscopeRepository,
        private ValidationUserPermissionInterface $validationUserPermission,
        private ConnectionInterface $connection
    )
    {
    }

    public function execute(CreateHoroscopeInput $input, string $userId): CreateHoroscopeOutput
    {
        try {
            $this->validationUserPermission->validate($userId, PermissionType::PUBLISH_HOROSCOPE);
            $builder = new HoroscopeBuilder()
                ->withStartDate($input->getStarDate())
                ->withEndDate($input->getEndDate());
            $this->connection->begin();
            foreach ($input->getMessages() as $item) {
                $horoscope = $builder
                    ->withMessage($item['message'])
                    ->withLuckNumber($item['luck_number'])
                    ->withZodiac(Zodiac::fromPrimitives($item['zodiac_id'], ''))
                    ->build();
                $this->horoscopeRepository->create($horoscope);
            }
            $this->connection->commit();
            return CreateHoroscopeOutput::success([]);
        } catch (GenericException $exception) {
            $this->connection->rollback();
            return CreateHoroscopeOutput::failure($exception->getStatusCode(), $exception->getData());
        } catch (\Exception $exception) {
            $this->connection->rollback();
            return CreateHoroscopeOutput::failure(500, ['exception' => $exception->getMessage()]);
        }
    }
}

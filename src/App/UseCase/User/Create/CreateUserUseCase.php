<?php

namespace App\App\UseCase\User\Create;

use App\App\Contracts\UserRepositoryInterface;
use App\App\Contracts\ZodiacRepositoryInterface;
use App\App\UseCase\User\Create\Input\CreateUserInput;
use App\App\UseCase\User\Create\Output\CreateUserOutput;
use App\Domain\Entity\User;
use App\Domain\Exceptions\ForbiddenException;
use App\Domain\Exceptions\InvalidParamsException;
use App\Domain\Exceptions\NotFoundException;

readonly class CreateUserUseCase
{
     public function __construct(
         private UserRepositoryInterface $userRepository,
         private ZodiacRepositoryInterface $zodiacRepository
     )
     {
     }

    public function execute(CreateUserInput $input): CreateUserOutput
    {
        try {
            $user = User::create($input);
            $zodiacMapper = $this->zodiacRepository->getSignByBirth($input->getBirthDate());
            $user->setZodiacSing($zodiacMapper);
            $created = $this->userRepository->create($user);
            return CreateUserOutput::success($created);
        } catch (InvalidParamsException $exception) {
            return CreateUserOutput::failure($exception->getStatusCode(), $exception->getData());
        }
    }
}
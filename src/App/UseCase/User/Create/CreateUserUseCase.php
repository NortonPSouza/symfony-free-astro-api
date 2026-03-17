<?php

namespace App\App\UseCase\User\Create;

use App\App\Contracts\PasswordEncoderInterface;
use App\App\Contracts\Repository\UserRepositoryInterface;
use App\App\Contracts\Repository\ZodiacRepositoryInterface;
use App\App\UseCase\User\Create\Input\CreateUserInput;
use App\App\UseCase\User\Create\Output\CreateUserOutput;
use App\Domain\Entity\User;
use App\Domain\Entity\Zodiac;
use App\Domain\Exceptions\RepositoryException;

readonly class CreateUserUseCase
{
     public function __construct(
         private UserRepositoryInterface $userRepository,
         private ZodiacRepositoryInterface $zodiacRepository
     )
     {
     }

    public function execute(CreateUserInput $input, PasswordEncoderInterface $passwordEncoder): CreateUserOutput
    {
        try {
            $user = User::create($input);
            $zodiacMapper = $this->zodiacRepository->getSignByBirth($input->getBirthDate());
            $zodiac = Zodiac::create($zodiacMapper->getId(), $zodiacMapper->getSign());
            $user->setZodiacSing($zodiac);
            $user->setEncryptedPassword($passwordEncoder);
            $created = $this->userRepository->create($user);
            return CreateUserOutput::success($created);
        } catch (RepositoryException $exception) {
            return CreateUserOutput::failure($exception->getStatusCode(), $exception->getData());
        }
    }
}
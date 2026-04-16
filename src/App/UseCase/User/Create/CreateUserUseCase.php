<?php

namespace App\App\UseCase\User\Create;

use App\App\Contracts\Repository\UserRepositoryInterface;
use App\App\Contracts\Repository\ZodiacRepositoryInterface;
use App\App\Contracts\Validation\PasswordEncoderInterface;
use App\App\Factory\UserFactory;
use App\App\UseCase\User\Create\Input\CreateUserInput;
use App\App\UseCase\User\Create\Output\CreateUserOutput;
use App\Domain\Entity\Zodiac;
use App\Domain\Exceptions\GenericException;

readonly class CreateUserUseCase
{
     public function __construct(
         private UserRepositoryInterface $userRepository,
         private ZodiacRepositoryInterface $zodiacRepository,
         private PasswordEncoderInterface $passwordEncoder
     )
     {
     }

    public function execute(CreateUserInput $input): CreateUserOutput
    {
        try {
            $user = UserFactory::fromInput($input);
            $zodiacMapper = $this->zodiacRepository->getSignByBirth($input->getBirthDate());
            $zodiac = Zodiac::create($zodiacMapper->getId(), $zodiacMapper->getSign());
            $user->setZodiacSing($zodiac);
            $user->setEncryptedPassword($this->passwordEncoder);
            $created = $this->userRepository->create($user);
            return CreateUserOutput::success($created);
        } catch (GenericException $exception) {
            return CreateUserOutput::failure($exception->getStatusCode(), $exception->getData());
        }
    }
}
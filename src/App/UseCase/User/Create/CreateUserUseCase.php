<?php

namespace App\App\UseCase\User\Create;

use App\App\Contracts\Repository\UserRepositoryInterface;
use App\App\Contracts\Repository\ZodiacRepositoryInterface;
use App\App\Contracts\Validation\PasswordEncoderInterface;
use App\App\UseCase\User\Create\Input\CreateUserInput;
use App\App\UseCase\User\Create\Output\CreateUserOutput;
use App\Domain\Builder\UserBuilder;
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
            $zodiac = $this->zodiacRepository->getSignByBirth($input->getBirthDate());
            $user = new UserBuilder()
                ->withName($input->getName())
                ->withFamilyName($input->getFamilyName())
                ->withEmail($input->getEmail())
                ->withPassword($input->getPassword())
                ->withEncryptedPassword($this->passwordEncoder)
                ->withBirthDate($input->getBirthDate())
                ->withBirthTime($input->getBirthTime())
                ->withZodiac($zodiac)
                ->build();
            $created = $this->userRepository->create($user);
            return CreateUserOutput::success(['id' => $created->getId()]);
        } catch (GenericException $exception) {
            return CreateUserOutput::failure($exception->getStatusCode(), $exception->getData());
        }
    }
}
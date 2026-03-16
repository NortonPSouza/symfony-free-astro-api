<?php

namespace App\App\UseCase\User\Find;

use App\App\Contracts\UserRepositoryInterface;
use App\App\UseCase\User\Find\Input\FindUserInput;
use App\App\UseCase\User\Find\Output\FindUserOutput;
use App\Domain\Exceptions\NotFoundException;
use App\Domain\Exceptions\RepositoryException;

readonly class FindUserUseCase
{

    public function __construct(
       private UserRepositoryInterface $userRepository
    )
    {
    }


    public function execute(FindUserInput $input): FindUserOutput
    {
        try {
            $user = $this->userRepository->find($input->getId());
            return FindUserOutput::success($user);
        } catch (RepositoryException|NotFoundException $exception) {
           return FindUserOutput::failure($exception->getStatusCode(), $exception->getData());
        }
    }
}
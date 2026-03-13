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

    /**
     * @throws NotFoundException
     */
    public function execute(FindUserInput $input): FindUserOutput
    {
        try {
            $userUpdated = $this->userRepository->find($input->getId());
            if (!$userUpdated) {
                throw  new NotFoundException("User not Found");
            }
            return FindUserOutput::success($userUpdated);
        } catch (RepositoryException $exception) {
           return FindUserOutput::failure($exception->getStatusCode(), $exception->getData());
        }
    }
}
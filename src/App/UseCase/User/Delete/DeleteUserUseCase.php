<?php

namespace App\App\UseCase\User\Delete;

use App\App\Contracts\UserRepositoryInterface;
use App\App\UseCase\User\Delete\Input\DeleteUserInput;
use App\App\UseCase\User\Delete\Output\DeleteUserOutput;
use App\Domain\Exceptions\NotFoundException;
use App\Domain\Exceptions\RepositoryException;

readonly class DeleteUserUseCase
{

    public function __construct(
        private UserRepositoryInterface $userRepository
    )
    {
    }

    public function execute(DeleteUserInput $input): DeleteUserOutput
    {
        try {
            $userMapper = $this->userRepository->find($input->getId());
            if (!$userMapper) {
                throw  new NotFoundException("User not Found");
            }
            $deleted = $this->userRepository->delete($userMapper);
            return DeleteUserOutput::success($deleted);
        } catch (RepositoryException|NotFoundException $exception) {
            return DeleteUserOutput::failure($exception->getStatusCode(), $exception->getData());
        }
    }
}
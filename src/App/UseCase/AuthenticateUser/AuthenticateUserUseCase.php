<?php

namespace App\App\UseCase\AuthenticateUser;

use App\App\Contracts\Gateway\TokenManagerInterface;
use App\App\Contracts\Repository\LoginRepositoryInterface;
use App\App\Contracts\Repository\UserRepositoryInterface;
use App\App\Contracts\Validation\PasswordEncoderInterface;
use App\App\UseCase\AuthenticateUser\Input\AuthenticateUserInput;
use App\App\UseCase\AuthenticateUser\Output\AuthenticateUserOutput;
use App\Domain\Exceptions\NotFoundException;
use App\Domain\Exceptions\RepositoryException;

readonly class AuthenticateUserUseCase
{

    public function __construct(
        private LoginRepositoryInterface $loginRepository,
        private UserRepositoryInterface $userRepository,
        private PasswordEncoderInterface $passwordEncoder,
        private TokenManagerInterface $tokenManager
    )
    {
    }

    public function execute(AuthenticateUserInput $input): AuthenticateUserOutput
    {
        try {
            $login = $this->loginRepository->findByEmail($input->getEmail());
            $passwordValid = $this->passwordEncoder->verify($input->getPassword(), $login->getPassword());
            if (!$passwordValid) {
                return AuthenticateUserOutput::failure(400, ['message' => "email or password wrong"]);
            }
            $user = $this->userRepository->findByEmail($input->getEmail());
            $refreshToken = $this->tokenManager->generate($user, 604800);
            $login->setRefreshToken($refreshToken)
                ->setExpiresIn(new \DateTime()->add(new \DateInterval('P7D')));
            $this->loginRepository->updateToken($login);
            $accessToken = $this->tokenManager->generate($user, 900);
            return AuthenticateUserOutput::success(['access_token' => $accessToken]);
        } catch (RepositoryException|NotFoundException $exception){
            return AuthenticateUserOutput::failure($exception->getStatusCode(), $exception->getData());
        }
    }
}
<?php

namespace App\App\UseCase\Authenticate;

use App\App\Contracts\Gateway\TokenManagerInterface;
use App\App\Contracts\Memory\TokenMemoryInterface;
use App\App\Contracts\Repository\UserRepositoryInterface;
use App\App\Contracts\Validation\PasswordEncoderInterface;
use App\App\UseCase\Authenticate\Input\AuthenticateInput;
use App\App\UseCase\Authenticate\Output\AuthenticateUserOutput;
use App\Domain\Exceptions\GenericException;
use App\Domain\Types\GrantTypeLogin;
use App\Domain\Types\LifeTimeToken;

readonly class AuthenticateUseCase
{

    public function __construct(
        private UserRepositoryInterface $userRepository,
        private PasswordEncoderInterface $passwordEncoder,
        private TokenManagerInterface $tokenManager,
        private TokenMemoryInterface $tokenMemory
    )
    {
    }

    public function execute(AuthenticateInput $input): AuthenticateUserOutput
    {
        try {
            if ($input->getGrantType() !== GrantTypeLogin::TOKEN->getType()) {
                $userId = $this->tokenManager->validate($input->getRefreshToken());
                $user = $this->userRepository->find($userId);
                $session = $this->tokenMemory->getSession($user->getId());
                $session->validateRefreshToken($input->getRefreshToken());
            } else {
                $user = $this->userRepository->findByEmail($input->getEmail());
                $this->passwordEncoder->verify($input->getPassword(), $user->getPassword()->getValue());
            }
            $refreshToken = $this->tokenManager->generate($user, LifeTimeToken::SEVEN_DAYS->getSeconds());
            $accessToken = $this->tokenManager->generate($user, LifeTimeToken::FIFTEEN_MINUTES->getSeconds());
            $payloadToken = [
                'access_token' => $accessToken->getToken(),
                'refresh_token' => $refreshToken->getToken(),
                'token_type' => 'Bearer'
            ];
            $this->tokenMemory->setSessions($user->getId(), $payloadToken, LifeTimeToken::SEVEN_DAYS->getSeconds());
            return AuthenticateUserOutput::success($payloadToken);
        } catch (GenericException $exception){
            return AuthenticateUserOutput::failure($exception->getStatusCode(), $exception->getData());
        }
    }
}
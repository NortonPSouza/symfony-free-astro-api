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
                $this->passwordEncoder->verify($input->getPassword(), $user->getPassword());
            }
            $refreshToken = $this->tokenManager->generate($user, 604800);
            $accessToken = $this->tokenManager->generate($user, 900);
            $payloadToken = [
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken,
                'token_type' => 'Bearer'
            ];
            $this->tokenMemory->setSessions($user->getId(), $payloadToken, 604800);
            return AuthenticateUserOutput::success($payloadToken);
        } catch (GenericException $exception){
            return AuthenticateUserOutput::failure($exception->getStatusCode(), $exception->getData());
        }
    }
}
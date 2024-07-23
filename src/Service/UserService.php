<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;

class UserService
{
    private UserRepository $userRepository;
    private ValidationService $validationService;
    private SecurityService $securityService;

    public function __construct(UserRepository $userRepository, ValidationService $validationService, SecurityService $securityService)
    {
        $this->userRepository = $userRepository;
        $this->validationService = $validationService;
        $this->securityService = $securityService;
    }

    public function signUpUser(string $username, string $password): array
    {
        $existingUser = $this->userRepository->findUserByUserName($username);
        $error = $this->validationService->validateSignUpData($username, $password, $existingUser);

        if (!empty($error))
        {
            return ['error' => $error];
        }

        $hashedPassword = $this->securityService->hashPassword($password);
        $user = new User(
            null,
            $username,
            $hashedPassword,
            1,
            0,
            0,
            0,
            0,
            0,
        );

        $this->userRepository->saveUserToDatabase($user);

        return ['user' => $user];
    }

    public function signInUser(string $username, string $password): array
    {
        $existingUser = $this->userRepository->findUserByUserName($username);
        $error = $this->validationService->validateSignInData($username, $password, $existingUser);

        if (!empty($error))
        {
            return ['error' => $error];
        }

        return ['user' => $existingUser];
    }

    public function getSortedUsers(): array
    {
        $usersMultiplayer = $this->userRepository->findAllUsers();
        $usersFirstLevel = $this->userRepository->findAllUsers();
        $usersSecondLevel = $this->userRepository->findAllUsers();
        $usersThirdLevel = $this->userRepository->findAllUsers();

        usort($usersMultiplayer, function ($a, $b) {

            $aAll = $a->getMultiplayerAll();
            $aWin = $a->getMultiplayerWin();

            $bAll = $b->getMultiplayerAll();
            $bWin = $b->getMultiplayerWin();

            $aRatio = $aAll > 0 ? $aWin / $aAll : 0;
            $bRatio = $bAll > 0 ? $bWin / $bAll : 0;

            return $bRatio <=> $aRatio;
        });
        usort($usersFirstLevel, function ($a, $b) {
            return $b->getScoreFirstLevel() - $a->getScoreFirstLevel();
        });
        usort($usersSecondLevel, function ($a, $b) {
            return $b->getScoreSecondLevel() - $a->getScoreSecondLevel();
        });
        usort($usersThirdLevel, function ($a, $b) {
            return $b->getScoreThirdLevel() - $a->getScoreThirdLevel();
        });

        return ['multiplayer' => $usersMultiplayer, 'first' => $usersFirstLevel, 'second' => $usersSecondLevel, 'third' => $usersThirdLevel];
    }

    public function setUserScore(User $user, int $currentLevel, int $newScore): void
    {
        if ($currentLevel === 1 && $user->getScoreFirstLevel() < $newScore)
        {
            $user->setScoreFirstLevel($newScore);
        }
        if ($currentLevel === 2 && $user->getScoreSecondLevel() < $newScore)
        {
            $user->setScoreSecondLevel($newScore);
        }
        if ($currentLevel === 3 && $user->getScoreThirdLevel() < $newScore)
        {
            $user->setScoreThirdLevel($newScore);
        }
    }

    public function setUserLevel(User $user, int $nextLevel): void
    {
        if ($user->getLevel() < $nextLevel)
        {
            $user->setLevel($nextLevel);
        }
    }
}
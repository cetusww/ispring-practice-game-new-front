<?php

namespace App\Repository;

use App\Entity\User;
use App\Service\SessionService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class UserRepository
{
    private EntityManagerInterface $entityManager;
    private EntityRepository $repository;
    private SessionService $sessionService;

    public function __construct(EntityManagerInterface $entityManager, SessionService $sessionService)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(User::class);
        $this->sessionService = $sessionService;
    }

    public function saveUserToDatabase(User $user): int
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $user->getId();
    }

    public function findUserByUserName(string $username): ?User
    {
        return $this->repository->findOneBy(['username' => $username]);
    }

    public function findAllUsers(): array
    {
        return $this->repository->findAll();
    }

    public function getCurrentUser(): ?User
    {
        $this->sessionService->startSession('auth');
        return $this->repository->findOneBy(['id' => $_SESSION['user_id']]);
    }

    public function getUserCurrentLevel(string $username): int
    {
        $user = $this->repository->findOneBy(['username' => $username]);
        return $user->getLevel();
    }

    public function updateUserProgress(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function updateUserMultiplayerProgress(string $username, string $opponentName): void
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $username]);
        $opponent = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $opponentName]);

        $user->setMultiplayerAll($user->getMultiplayerAll() + 1);
        $user->setMultiplayerWin($user->getMultiplayerWin() + 1);

        $opponent->setMultiplayerAll($opponent->getMultiplayerAll() + 1);

        $this->entityManager->flush();
    }
}
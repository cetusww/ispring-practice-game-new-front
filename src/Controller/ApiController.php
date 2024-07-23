<?php

namespace App\Controller;

use App\Service\SessionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\UserRepository;
use App\Service\UserService;

class ApiController extends AbstractController
{
    private UserRepository $userRepository;
    private SessionService $sessionService;
    private UserService $userService;

    public function __construct(UserRepository $userRepository, SessionService $sessionService, UserService $userService)
    {
        $this->userRepository = $userRepository;
        $this->sessionService = $sessionService;
        $this->userService = $userService;
    }

    public function saveScore(Request $request): Response
    {
        $this->sessionService->startSession('auth');

        $data = json_decode($request->getContent(), true);

        $user = $this->userRepository->findUserByUserName($_SESSION['username']);

        $_SESSION['level'] = $data['nextLvl'];

        $this->userService->setUserLevel($user, $data['nextLvl']);
        $this->userService->setUserScore($user, $data['currentLvl'], $data['score']);

        $this->userRepository->updateUserProgress($user);

        return new Response('Score updated successfully for user id ' . $_SESSION['user_id']);
    }

    public function saveMultiplayerScore(Request $request): Response
    {
        $this->sessionService->startSession('auth');

        $data = json_decode($request->getContent(), true);

        $this->userRepository->updateUserMultiplayerProgress($data['username'], $data['opponentname']);

        return new Response('Score updated successfully for user id ' . $_SESSION['user_id']);
    }
}
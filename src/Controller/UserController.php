<?php

namespace App\Controller;

use App\Service\SessionService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class UserController extends AbstractController
{
    private UserService $userService;
    private SessionService $sessionService;

    public function __construct(UserService $userService, SessionService $sessionService)
    {
        $this->userService = $userService;
        $this->sessionService = $sessionService;
    }

    public function index(): Response
    {
        return $this->render('signup_user_form.html.twig');
    }

    public function signUpUser(Request $request): Response
    {
        $newPassword = $request->get('password');
        $newUsername = $request->get('username');

        $result = $this->userService->signUpUser($newUsername, $newPassword);

        if (isset($result['error']))
        {
            return $this->render('signup_user_form.html.twig', ['error' => $result['error']]);
        }

        $user = $result['user'];
        $this->sessionService->startSession('auth');
        $this->sessionService->setUserSession($user->getId(), $user->getUsername(), $user->getLevel());

        return $this->redirectToRoute('show_menu');
    }

    public function signInUser(Request $request): Response
    {
        $username = $request->get('username');
        $password = $request->get('password');

        $result = $this->userService->signInUser($username, $password);

        if (isset($result['error']))
        {
            return $this->render('signin_user_form.html.twig', ['error' => $result['error']]);
        }

        $user = $result['user'];
        $this->sessionService->startSession('auth');
        $this->sessionService->setUserSession($user->getId(), $user->getUsername(), $user->getLevel());

        return $this->redirectToRoute('show_menu');
    }

    public function signOutUser(): Response
    {
        $this->sessionService->destroySession('auth');

        return $this->redirectToRoute('index');
    }
}
<?php

namespace App\Service;

use App\Entity\User;
use App\Service\SecurityService;

class ValidationService
{
    private SecurityService $securityService;

    public function __construct(SecurityService $securityService)
    {
        $this->securityService = $securityService;
    }

    public function validateSignUpData(string $username, string $password, ?User $existingUser): array
    {
        $error = [];

        if (strlen($username) < 3)
        {
            $error['username'] = 'Имя пользователя должно содержать минимум 3 символа';
        }

        if (strlen($username) > 9)
        {
            $error['username'] = 'Имя пользователя должно содержать максимум 9 символов';
        }

        if (preg_match('/[^a-zA-Zа-яА-Я0-9]/u', $username)) {
            $error['username'] = 'Имя пользователя не должно содержать специальные символы';
        }

        if (empty($password))
        {
            $error['password'] = 'Пароль не может быть пустым';
        }

        if (strlen($password) < 6)
        {
            $error['password'] = 'Пароль должен содержать не менее 6 символов';
        }

        if ($existingUser)
        {
            $error['username'] = 'Пользователь с таким именем уже существует';
        }

        return $error;
    }

    public function validateSignInData(string $username, string $password, ?User $existingUser): array
    {
        $error = [];

        if (empty($password))
        {
            $error['password'] = 'Пароль не может быть пустым';
        }

        if (!$existingUser)
        {
            $error['username'] = 'Пользователя с таким именем не существует';
        }

        if ($existingUser && !$this->securityService->verifyPassword($password, $existingUser->getPassword()))
        {
            $error['password'] = 'Неверный пароль';
        }

        return $error;
    }
}
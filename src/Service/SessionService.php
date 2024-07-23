<?php

namespace App\Service;

class SessionService
{
    public function startSession(string $name): void
    {
        session_name($name);
        session_start();
    }

    public function setUserSession(int $userId, string $username, int $level): void
    {
        $_SESSION['user_id'] = $userId;
        $_SESSION['username'] = $username;
        $_SESSION['level'] = $level;
    }

    public function destroySession(string $name): void
    {
        session_name($name);
        session_start();
        session_destroy();
    }
}
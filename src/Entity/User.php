<?php

namespace App\Entity;

class User
{
    public function __construct(
        private ?int $id,
        private string $username,
        private string $password,
        private ?int $level,
        private ?int $scoreFirstLevel,
        private ?int $scoreSecondLevel,
        private ?int $scoreThirdLevel,
        private ?int $multiplayerAll,
        private ?int $multiplayerWin,
    )
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserName(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function getScoreFirstLevel(): ?int
    {
        return $this->scoreFirstLevel;
    }

    public function getScoreSecondLevel(): ?int
    {
        return $this->scoreSecondLevel;
    }

    public function getScoreThirdLevel(): ?int
    {
        return $this->scoreThirdLevel;
    }

    public function getMultiplayerAll(): ?int
    {
        return $this->multiplayerAll;
    }

    public function getMultiplayerWin(): ?int
    {
        return $this->multiplayerWin;
    }

    public function setUserName(string $username): string
    {
        return $this->username = $username;
    }

    public function setPassword(string $password): string
    {
        return $this->password = $password;
    }

    public function setLevel(int $level): int
    {
        return $this->level = $level;
    }

    public function setScoreFirstLevel(string $scoreFirstLevel): int
    {
        return $this->scoreFirstLevel = $scoreFirstLevel;
    }

    public function setScoreSecondLevel(string $scoreSecondLevel): int
    {
        return $this->scoreSecondLevel = $scoreSecondLevel;
    }

    public function setScoreThirdLevel(string $scoreThirdLevel): int
    {
        return $this->scoreThirdLevel = $scoreThirdLevel;
    }

    public function setMultiplayerAll(int $multiplayerAll): int
    {
        return $this->multiplayerAll = $multiplayerAll;
    }

    public function setMultiplayerWin(int $multiplayerWin): int
    {
        return $this->multiplayerWin = $multiplayerWin;
    }
}
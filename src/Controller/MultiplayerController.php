<?php

namespace App\Controller;

use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class MultiplayerController implements MessageComponentInterface
{
    protected \SplObjectStorage $clients;
    public array $arrayOfUsers = [];

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn): void
    {
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg): void
    {
        if (!$msg)
        {
            echo "Invalid message\n";
        }

        $data = json_decode($msg, true);
        $kick = false;
        if (isset($data['username']))
        {
            if (count($this->arrayOfUsers) === 1)
            {
                $keys = array_keys($this->arrayOfUsers);
                if ($this->arrayOfUsers[$keys[0]] === $data['username'])
                {
                    $kick = true;
                }
            }
            if (count($this->arrayOfUsers) === 2 || $kick)
            {
                $from->send(json_encode('kick'));
            }
            else
            {
                $this->arrayOfUsers[$from->resourceId] = $data['username'];
                if (count($this->arrayOfUsers) === 2)
                {
                    $players = [];
                    foreach ($this->clients as $client)
                    {
                        $players[] = ['user' => $client, 'name' => $this->arrayOfUsers[$client->resourceId]];
                    }
                    $time = microtime(true) * 1000;
                    $players[0]['user']->send(json_encode(['state' => ['state' => 'ready', 'time' => $time], 'opponentname' => $players[1]['name'], 'heropos' => true]));
                    $players[1]['user']->send(json_encode(['state' => ['state' => 'ready', 'time' => $time], 'opponentname' => $players[0]['name']]));
                }
            }

        }
        if (isset($data['playerdata']))
        {
            $playerData = $data['playerdata'];
            foreach ($this->clients as $client)
            {
                if ($client->resourceId !== $from->resourceId)
                {
                    if ($playerData['opponentlose'] == 'true')
                    {
                        $client->send(json_encode('lose'));
                        echo 'lose';
                    }
                    else
                    {
                        $client->send(json_encode(
                            [
                                'opponentupdate' =>
                                    [
                                        'x' => $playerData['x'],
                                        'y' => $playerData['y'],
                                        'hp' => $playerData['hp'],
                                        'animatetype' => $playerData['animatetype'],
                                        'scalex' => $playerData['scalex'],
                                        'opponentbullets' => $playerData['herobullets'],
                                        'removebonus' => $playerData['removebonus'],
                                        'damage' => $playerData['damage'],
                                    ],
                            ]));
                    }
                }
            }
        }
        if (isset($data['msg']))
        {
            if ($data['msg'] == 'PING')
            {
                $from->send(json_encode('Server Pong'));
            }
        }
    }

    public function onClose(ConnectionInterface $conn): void
    {
        $this->clients->detach($conn);
        $id = $conn->resourceId;
        echo "Connection {$id} has disconnected\n";
        unset($this->arrayOfUsers[$id]);
        $time = microtime(true) * 1000;
        foreach ($this->clients as $client)
        {
            $client->send(json_encode(['state' => ['state' => 'stop', 'time' => $time]]));
        }
    }

    public function onError(ConnectionInterface $conn, \Exception $e): void
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}
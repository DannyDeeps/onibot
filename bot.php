<?php

use Discord\Discord;
use Discord\Parts\Channel\Message;
use Psr\Http\Message\ResponseInterface;
use React\EventLoop\Factory;
use React\Http\Browser;

require __DIR__ . '/vendor/autoload.php';

$loop = Factory::create();
$discord = new Discord([
    'token' => 'ODI1MTU0MTc3NzcyMTU5MDM2.YF5ytg.c6tGhN9N4XPDJQnxeXU0An3EtFw',
    'loop' => $loop,
    'disabledEvents' => []
]);

$discord->on('ready', function(Discord $discord) {
    $discord->on('message', function (Message $message, Discord $discord) {
        switch (strtolower($message->content)) {
            case '!initrole':
                $message = new Message($discord);
                $message->content= 'Test';
                $discord->guilds->channels->
                break;
        }
    });
});


$discord->run();
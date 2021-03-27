<?php

use Discord\Discord;
use Discord\Parts\Channel\{ Guild, Channel, Message };
use React\EventLoop\Factory;
use Psr\Http\Message\ResponseInterface;
use React\Http\Browser;

require __DIR__ . '/vendor/autoload.php';

$loop = Factory::create();
// $browser= new Browser($loop);
$discord = new Discord([
    'token' => 'ODI1MTU0MTc3NzcyMTU5MDM2.YF5ytg.c6tGhN9N4XPDJQnxeXU0An3EtFw',
    'loop' => $loop,
    'disabledEvents' => []
]);

$discord->on('ready', function(Discord $discord) {

    $discord->on('message', function (Message $message, Discord $discord) {
        switch (strtolower($message->content)) {
            case '!initrole':
                $selectRoleMsg= new Message($discord, [
                    'channel_id' => '12345',
                    'content' => 'Select a reaction to designate your role.'
                ]);
                $selectRoleMsg->reply($selectRoleMsg->content);
                break;
        }
    });

});


$discord->run();
<?php

use Discord\Discord;
use Discord\Parts\Channel\{ Guild, Channel, Message };
use React\EventLoop\Factory;

require __DIR__ . '/vendor/autoload.php';

$loop = Factory::create();
$discord = new Discord([
    'token' => 'ODI1MTU0MTc3NzcyMTU5MDM2.YF5ytg.c6tGhN9N4XPDJQnxeXU0An3EtFw',
    'loop' => $loop,
    'disabledEvents' => []
]);

$discord->on('ready', function(Discord $discord)
{
    $discord->on('message', function (Message $message, Discord $discord)
    {
        switch (strtolower($message->content)) {
            case '!initrole':
                $channel = $discord->getChannel('825144851267977256');
                $channel->sendMessage('Select a reaction to designate your role!')->done(function(Message $msg) {
                    $msg->react(':Heal:825145748936589312')->done(function() {
                        echo "Reaction added";
                    }, function($e) {
                        echo "Error: " . $e->getMessage();
                    });
                }, function($e) {
                    echo "Error: {$e->getMessage()}";
                });
                break;
            case '!initregion':
                $channel = $discord->getChannel('825144851267977256');
                $channel->sendMessage('Select a reaction to designate your region!')->done(function(Message $msg) {
                    $msg->react(':EU:');
                    $msg->react(':NA:');
                }, function($e) {
                    echo "Error: {$e->getMessage()}";
                });
                break;
        }
    });
});

$discord->run();
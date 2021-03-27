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
                $channel = $discord->getChannel('825144851267977256');
                $channel->sendMessage('Select a reaction to designate your role!')->done(function() {
                    echo "Message Sent\r\n";
                }, function($e) {
                    echo "Error: " . $e->getMessage();
                });
                break;
        }
    });

});


$discord->run();

// hi all, just started looking at this library and im trying to create a command that will initialise a message in a 'roles' text channel, this is where I am so far
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
                $channel->sendMessage('Select a reaction to designate your role!')->done(success('Role Message Initialised'), error($e, 'Role Message'));
                break;
            case '!initregion':
                $channel = $discord->getChannel('825144851267977256');
                $channel->sendMessage('Select a reaction to designate your region!')->done(success('Region Message Initialised'), error($e, 'Region Message'));
                break;
        }
    });
});

function success(String $msg)
{
    echo $msg."\r\n";
}
function error($e, String $msg)
{
    echo $msg . ": " . $e->getMessage() . "\r\n";
}


$discord->run();

// hi all, just started looking at this library and im trying to create a command that will initialise a message in a 'roles' text channel, this is where I am so far
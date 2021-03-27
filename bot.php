<?php

use Discord\Discord;
use Discord\WebSockets\Event;
use Discord\Parts\Channel\{ Guild, Channel, Message };
use Discord\Parts\Embed\Embed;
use Discord\Parts\WebSockets\MessageReaction;
use React\Http\Message\Response;
use React\EventLoop\Factory;
use Oni\Reacts;

require __DIR__ . '/vendor/autoload.php';

$loop = Factory::create();
$discord = new Discord([
    'token' => 'ODI1MTU0MTc3NzcyMTU5MDM2.YF5ytg.c6tGhN9N4XPDJQnxeXU0An3EtFw',
    'loop' => $loop,
    'disabledEvents' => []
]);

$discord->on('ready', function(Discord $discord)
{
    $discord->on(Event::MESSAGE_CREATE, function (Message $message, Discord $discord)
    {
        switch (strtolower($message->content)) {
            case '!initrole':
                $results= [];
                $reacts= [Reacts::HEAL, Reacts::TANK, Reacts::RANGE, Reacts::ATTACK];
                $embed= new Embed($discord, [
                    'title' => 'Classes',
                    'description' => 'Select the reactions below to be assigned the roles you prefer to play.',
                    'color' => '#00FF00'
                ]);

                $channel = $discord->getChannel('825144851267977256');
                $promise= $channel->sendMessage('', false, $embed);
                foreach ($reacts as $react) {
                    $promise->then(function(Message $message) use ($react) {
                        $results[]= $message->react($react);
                    }, function($e) {
                        echo "Error: " . $e->getMessage();
                    });
                }

                $promise->done(function() use ($results) {
                    return new Response(200, ['Content-Type' => 'application/json'], json_encode($results));
                });
                break;
            case '!initregion':
                $results= [];
                $reacts= [Reacts::EU, Reacts::NA];
                $embed= new Embed($discord, [
                    'title' => 'Region',
                    'description' => 'Select the reactions below to be assigned the region you prefer to play on.',
                    'color' => '#00FF00'
                ]);

                $channel = $discord->getChannel('825144851267977256');
                $promise= $channel->sendMessage('', false, $embed);
                foreach ($reacts as $react) {
                    $promise->then(function(Message $message) use ($react) {
                        $results[]= $message->react($react);
                    }, function($e) {
                        echo "Error: " . $e->getMessage();
                    });
                }

                $promise->done(function() use ($results) {
                    return new Response(200, ['Content-Type' => 'application/json'], json_encode($results));
                });
                break;
        }
    });

    $discord->on(Event::MESSAGE_REACTION_ADD, function(MessageReaction $reaction, Discord $discord)
    {
        echo print_r($reaction, true);
    });
});

$discord->run();